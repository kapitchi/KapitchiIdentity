<?php

namespace KapitchiIdentity\Service;

use Zend\Authentication\AuthenticationService as ZendAuthenticationService,
        Zend\Di\Locator,
        Exception as NoLocalIdException,
        Exception as NoLoggedInException,
    KapitchiIdentity\Model\AuthIdentity,
    KapitchiIdentity\Service\Acl,
    Zend\Authentication\Adapter,
        Zend\EventManager\EventCollection,
        Zend\EventManager\EventManager;

class Auth extends ZendAuthenticationService {
    
    protected $locator;
    protected $events;

    public function authenticate(Adapter $adapter) {
        $result = $adapter->authenticate();

        if($this->hasIdentity()) {
            $this->clearIdentity();
        }

        $this->events()->trigger('authenticate.post', array(
            'result' => $result,
            'adapter' => $adapter,
        ));
        
        if($result->isValid()) {
            if($adapter instanceof Auth\AuthIdentityResolver) {
                $authIdentity = $adapter->resolveAuthIdentity($result->getIdentity());
            }
            else {
                $authIdentity = new AuthIdentity($result->getIdentity(), Acl::ROLE_AUTH);
            }
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
        $this->getStorage()->clear();
        
        $this->events()->trigger('clearIdentity.post');
    }
    
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
    
    public function setLocator(Locator $locator) {
        $this->locator = $locator;
    }
    
    public function getLocator() {
        return $this->locator;
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