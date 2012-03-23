<?php

namespace KapitchiIdentity\Service;

use     Zend\Authentication\AuthenticationService as ZendAuthenticationService,
        Zend\Di\Locator,
        Zend\Authentication\Adapter,
        Zend\EventManager\EventCollection,
        Zend\EventManager\EventManager,
        Zend\Acl\Role\GenericRole,
        KapitchiIdentity\Model\AuthIdentity,
        Exception as NoLocalIdException,
        Exception as NoLoggedInException;
        
class Auth extends ZendAuthenticationService {
    
    protected $events;
    protected $role;

    public function authenticate(Adapter $adapter) {
        $result = $adapter->authenticate();

        if($this->hasIdentity()) {
            $this->clearIdentity();
        }

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
            
            $this->getStorage()->write($authIdentity);
        }
        
        return $result;
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
            throw new NoLocalIdException("User has got no local identity");
        }
        
        return $localId;
    }
    
    /**
     * Set the event manager instance used by this context
     * 
     * @param  EventCollection $events 
     * @return mixed
     */
    public function setEventManager(EventCollection $events)
    {
        $this->events = $events;
        return $this;
    }
    
    /**
     * Retrieve the event manager
     *
     * Lazy-loads an EventManager instance if none registered.
     * 
     * @return EventCollection
     */
    public function events()
    {
        if (!$this->events instanceof EventCollection) {
            $this->setEventManager(new EventManager(array(__CLASS__, get_class($this))));
            $this->attachDefaultListeners();
        }
        return $this->events;
    }
    
    protected function attachDefaultListeners() {
        $events = $this->events();
        
        //$events->attach('invalidateCache', array($this, 'invalidateSessionCache'), -10);
    }
    
}