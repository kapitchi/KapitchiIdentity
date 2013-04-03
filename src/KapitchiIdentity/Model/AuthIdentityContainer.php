<?php
namespace KapitchiIdentity\Model;

class AuthIdentityContainer
{
    protected $identities;
    protected $defaultSessionId;
    protected $currentSessionId;
    
    public function __construct()
    {
        $this->reset();
    }
    
    public function reset() {
        $this->identities = array();
        $this->defaultSessionId = null;
    }
    
    public function getIdentities()
    {
        return $this->identities;
    }

    /**
     * This method generates and set session id to AuthIdentity
     * If no defaultSessionId/currentSessionId is set it sets it to just created one
     * 
     * @throws \InvalidArgumentException if same AuthIdentity is already added to the container
     * @param \KapitchiIdentity\Model\AuthIdentity $identity
     */
    public function add(AuthIdentityInterface $identity) {
        if($this->has($identity)) {
            throw new \InvalidArgumentException("Same identity exists in the container");
        }
        
        //sets container generated session id for this idenity
        $nextSessionId = count($this->identities);
        $identity->setSessionId($nextSessionId);
        
        $this->identities[] = $identity;
        
        if($this->getDefaultSessionId() === null) {
            $this->setDefaultSessionId($nextSessionId);
        }
        
        if($this->getCurrentSessionId() === null) {
            $this->setCurrentSessionId($nextSessionId);
        }
    }
    
    public function getBySessionId($sessionId) {
        foreach($this->getIdentities() as $id) {
            if($id->getSessionId() == $sessionId) {
                return $id;
            }
        }
        
        return null;
    }
    
    public function has(AuthIdentityInterface $identity)
    {
        $ids = $this->getIdentities();
        foreach($ids as $id) {
            if($id->isEqual($identity)) {
                return true;
            }
        }
        
        return false;
    }

    public function getDefaultSessionId()
    {
        return $this->defaultSessionId;
    }

    public function setDefaultSessionId($defaultSessionId)
    {
        $this->defaultSessionId = $defaultSessionId;
    }
    
    public function getCurrentSessionId()
    {
        return $this->currentSessionId;
    }

    public function setCurrentSessionId($currentSessionId)
    {
        $this->currentSessionId = $currentSessionId;
    }
    
}