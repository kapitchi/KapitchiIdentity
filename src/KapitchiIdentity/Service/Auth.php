<?php
/**
 * Kapitchi Zend Framework 2 Modules (http://kapitchi.com/)
 *
 * @copyright Copyright (c) 2012-2013 Kapitchi Open Source Team (http://kapitchi.com/open-source-team)
 * @license   http://opensource.org/licenses/LGPL-3.0 LGPL 3.0
 */

namespace KapitchiIdentity\Service;

use Zend\Authentication\Adapter\AdapterInterface,
    Zend\EventManager\EventManagerAwareInterface,
    Zend\EventManager\EventManagerInterface,
    Zend\EventManager\EventManager,
    KapitchiIdentity\Model\GenericAuthIdentity,
    KapitchiIdentity\Model\AuthIdentityInterface,
    KapitchiIdentity\Authentication\IdentityResolverInterface;
        
class Auth extends \Zend\Authentication\AuthenticationService implements EventManagerAwareInterface {
    
    /**
     * @var EventManagerInterface
     */
    protected $eventManager;
    protected $identityMapper;
    protected $containerHydrator;
    protected $sessionProvider;
    
    /**
     * This represents current auth identity of this request
     * @var \KapitchiIdentity\Model\AuthIdentity
     */
    protected $identity;

    public function authenticate(AdapterInterface $adapter = null) {
        if (!$adapter) {
            if (!$adapter = $this->getAdapter()) {
                throw new \Zend\Authentication\Exception\RuntimeException('An adapter must be set or passed prior to calling authenticate()');
            }
        }
        $result = $adapter->authenticate();
        
        if($result->isValid()) {
            $identityId = null;
            if($adapter instanceof IdentityResolverInterface) {
                $identityId = $adapter->resolveIdentityId($result->getIdentity());
                $idEntity = $this->getIdentityMapper()->find($identityId);
                if(!$idEntity->getAuthEnabled()) {
                    return new \Zend\Authentication\Result(\Zend\Authentication\Result::FAILURE, $result->getIdentity(), array(
                        'identity' => 'Identity authetication disabled'
                    ));
                }
            }

            $authIdentity = new GenericAuthIdentity($result->getIdentity(), $identityId);
            if($this->getContainer()->has($authIdentity)) {
                return new \Zend\Authentication\Result(\Zend\Authentication\Result::FAILURE, $result->getIdentity(), array(
                    'identity' => 'Already logged in with this identity'
                ));
            }
            $this->addIdentity($authIdentity);
            
            $this->getEventManager()->trigger('authenticate.valid', $this, array(
                'result' => $result,
                'adapter' => $adapter,
                'authIdentity' => $authIdentity,
            ));
            
            return $result;
        }
        
        //invalid state
        $this->getEventManager()->trigger('authenticate.invalid', $this, array(
            'result' => $result,
            'adapter' => $adapter,
        ));
        
        return $result;
    }
    
    /**
     * @todo should authenticate() be only way how to add identities?
     * @param \KapitchiIdentity\Model\AuthIdentityInterface $authIdentity
     */
    protected function addIdentity(AuthIdentityInterface $authIdentity) {
        $container = $this->loadContainer();
        $container->add($authIdentity);
        $this->storeContainer($container);
    }
    
    /**
     * @todo Do we want this method here? Should not we stricly work with container only?
     * @deprecated
     * @param \KapitchiIdentity\Model\AuthIdentityInterface $authIdentity
     */
    public function setIdentity(AuthIdentityInterface $authIdentity) {
        $this->identity = $authIdentity;
    }
    
    /**
     * 
     * @todo Container should be stupid as possible - should we implement what getBySessionId() does here instead?
     * @return AuthIdentityInterface
     * @throws \RuntimeException
     */
    public function getIdentity()
    {
        if($this->identity === null) {
            $container = $this->loadContainer();
            $sessionId = $this->getCurrentSessionId();
            if(!$sessionId) {
                $sessionId = $container->getDefaultSessionId();
            }
            
            $identity = $container->getBySessionId($sessionId);
            if(!$identity) {
                //there is obviosusly something wrong with either session provider or container?
                $this->getSessionProvider()->clear();
                //throw new \RuntimeException("No identity registered under session ID '$sessionId'");
            }
            
            $this->identity = $identity;
        }
        
        return $this->identity;
    }
    
    protected function getCurrentSessionId()
    {
        return $this->getSessionProvider()->getCurrentSessionId();
    }
    
    /**
     * Returns true if and only if an identity is available from storage
     *
     * @return bool
     */
    public function hasIdentity()
    {
        return $this->getIdentity() !== null;
    }

    /**
     * Clears the identity from persistent storage
     *
     * @return array AuthIdentity
     */
    public function clearIdentity()
    {
        $this->getStorage()->clear();

        //all identies go - it's done that way now at least
        $ids = $this->getContainer()->getIdentities();
        $sessionIds = array();
        foreach($ids as $id) {
            $sessionIds[] = $id->getSessionId();
        }
        $this->getSessionProvider()->clear($sessionIds);
        
        $this->getEventManager()->trigger('clearIdentity.post', $this, array(
            'identities' => $ids
        ));
        
        return $ids;
    }
    
    public function getContainer()
    {
        return $this->loadContainer();
    }
    
    /**
     * @return int
     */
    public function getLocalIdentityId() {
        if(!$this->hasIdentity()) {
            throw new \Exception("User is not logged in");
        }
        
        $authIdentity = $this->getIdentity();
        
        $localId = $authIdentity->getId();
        if(empty($localId)) {
            return null;
        }
        
        return $localId;
    }
    
    /**
     * @todo this needs proper exception handling
     * @return \KapitchiIdentity\Model\AuthIdentityContainer
     */
    protected function loadContainer() {
        try {
            $data = $this->getStorage()->read();
            if(!is_array($data)) {
                throw new \Exception("Storage data is not array");
            }
            $container = $this->getContainerHydrator()->hydrate($data, new \KapitchiIdentity\Model\AuthIdentityContainer());
        } catch(\Exception $e) {
            //there was a problem to retrieve container - session, hydrator? - reset it!
            $container = new \KapitchiIdentity\Model\AuthIdentityContainer();
        }
        return $container;
    }

    protected function storeContainer(\KapitchiIdentity\Model\AuthIdentityContainer $container) {
        $data = $this->getContainerHydrator()->extract($container);
        $this->getStorage()->write($data);
    }
    
    public function setEventManager(EventManagerInterface $events)
    {
        $this->eventManager = $events;
        $this->attachDefaultListeners();
        return $this;
    }

    /**
     * Retrieve the event manager
     *
     * Lazy-loads an EventManager instance if none registered.
     * 
     * @return EventManagerInterface
     */
    public function getEventManager()
    {
        if (!$this->eventManager instanceof EventManagerInterface) {
            $this->setEventManager(new EventManager(array(__CLASS__, get_called_class())));
        }
        return $this->eventManager;
    }
    
    protected function attachDefaultListeners() {
        
    }
    
    public function getIdentityMapper()
    {
        return $this->identityMapper;
    }

    public function setIdentityMapper($identityMapper)
    {
        $this->identityMapper = $identityMapper;
    }
    
    /**
     * 
     * @return \KapitchiIdentity\Service\AuthSessionProvider\AuthSessionProviderInterface
     */
    public function getSessionProvider()
    {
        return $this->sessionProvider;
    }

    public function setSessionProvider($sessionProvider)
    {
        $this->sessionProvider = $sessionProvider;
    }
        
    /**
     * 
     * @return Zend\Stdlib\Hydrator\HydratorInterface
     */
    public function getContainerHydrator()
    {
        return $this->containerHydrator;
    }

    public function setContainerHydrator($hydrator)
    {
        $this->containerHydrator = $hydrator;
    }

}