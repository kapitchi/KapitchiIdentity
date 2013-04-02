<?php

namespace KapitchiIdentity\Service;

use Zend\Authentication\Adapter\AdapterInterface,
    Zend\EventManager\EventManagerAwareInterface,
    Zend\Authentication\AuthenticationService,
    Zend\EventManager\EventManagerInterface,
    Zend\EventManager\EventManager,
    KapitchiIdentity\Model\AuthIdentity,
    KapitchiIdentity\Authentication\IdentityResolverInterface;
        
class Auth extends AuthenticationService implements EventManagerAwareInterface {
    
    /**
     * @var EventManagerInterface
     */
    protected $eventManager;
    protected $identityMapper;
    protected $containerHydrator;

    public function authenticate(AdapterInterface $adapter) {
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
            
            $authIdentity = new AuthIdentity($result->getIdentity(), $identityId);
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
    
    public function addIdentity(AuthIdentity $authIdentity) {
        $container = $this->loadContainer();
        $container->addIdentity($authIdentity);
        $this->storeContainer($container);
    }
    
    public function setIdentity(AuthIdentity $authIdentity) {
        if($this->hasIdentity()) {
            $this->clearIdentity();
        }
        
        $this->addIdentity($authIdentity);
    }
    
    public function getIdentity()
    {
        $container = $this->loadContainer();
        return $container->getDefaultIdentity();
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
     * @return void
     */
    public function clearIdentity()
    {
        $id = $this->getIdentity();
        
        $this->getStorage()->clear();
        
        $this->getEventManager()->trigger('clearIdentity.post', $this, array(
           'authIdentity' => $id 
        ));
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