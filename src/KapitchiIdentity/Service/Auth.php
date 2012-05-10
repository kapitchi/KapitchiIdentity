<?php

namespace KapitchiIdentity\Service;

use     Zend\Authentication\AuthenticationService as ZendAuthenticationService,
        Zend\Di\Locator,
        Zend\Authentication\Adapter,
        Zend\EventManager\EventManagerInterface,
        Zend\EventManager\EventManager,
        Zend\Acl\Role\GenericRole,
        KapitchiIdentity\Model\AuthIdentity,
        Exception as NoLocalIdException,
        Exception as NoLoggedInException;
        
class Auth extends ZendAuthenticationService {
    
    protected $events;

    public function authenticate(Adapter $adapter) {
        $result = $adapter->authenticate();

        if($result->isValid()) {
            if($adapter instanceof AuthIdentityResolver) {
                $authIdentity = $adapter->resolveAuthIdentity($result->getIdentity());
            }
            else {
                $authIdentity = new AuthIdentity($result->getIdentity());
            }
            
            $this->events()->trigger('authenticate.valid', array(
                'result' => $result,
                'adapter' => $adapter,
                'authIdentity' => $authIdentity,
            ));
            
            $this->setIdentity($authIdentity);
        }
        
        return $result;
    }
    
    public function setIdentity(AuthIdentity $authIdentity) {
        if($this->hasIdentity()) {
            $this->clearIdentity();
        }
        
        $this->getStorage()->write($authIdentity);
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
        
        $this->events()->trigger('clearIdentity.post', $this, array(
           'authIdentity' => $id 
        ));
    }
    
    /**
     * @return int
     */
    public function getLocalIdentityId() {
        if(!$this->hasIdentity()) {
            throw new NoLoggedInException("User is not logged in");
        }
        
        $authIdentity = $this->getIdentity();
        $localId = $authIdentity->getLocalIdentityId();
        if(empty($localId)) {
            return null;
        }
        
        return $localId;
    }
    
    /**
     * Set the event manager instance used by this context
     * 
     * @param  EventManagerInterface $events 
     * @return mixed
     */
    public function setEventManager(EventManagerInterface $events)
    {
        $this->events = $events;
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
    public function events()
    {
        if (!$this->events instanceof EventManagerInterface) {
            $this->setEventManager(new EventManager(array(__CLASS__, get_class($this))));
        }
        return $this->events;
    }
    
    protected function attachDefaultListeners() {
        
    }
    
}