<?php
namespace KapitchiIdentity\Model;

class AuthIdentityContainer
{
    protected $identities;
    protected $defaultIdentity;
    
    public function __construct()
    {
        $this->reset();
    }
    
    public function reset() {
        $this->identities = array();
        $this->defaultIdentity = null;
    }
    
    public function getIdentities()
    {
        return $this->identities;
    }

    public function setIdentities(array $identities)
    {
        $this->identities = $identities;
    }
    
    /**
     * @param \KapitchiIdentity\Model\AuthIdentity $identity
     */
    public function addIdentity(AuthIdentity $identity) {
        if($this->hasIdentity($identity)) {
            throw new \InvalidArgumentException("Same identity exists in the container");
        }
        
        $this->identities[] = $identity;
        
        if($this->getDefaultIdentity() === null) {
            $this->setDefaultIdentity($identity);
        }
    }
    
    public function getDefaultIdentity()
    {
        return $this->defaultIdentity;
    }

    /**
     * @param \KapitchiIdentity\Model\AuthIdentity $defaultIdentity
     */
    public function setDefaultIdentity(AuthIdentityInterface $defaultIdentity)
    {
        if(!$this->hasIdentity($defaultIdentity)) {
            throw new \InvalidArgumentException("Idenity has to be added in the container first in order to make it default");
        }
        
        $this->defaultIdentity = $defaultIdentity;
    }
    
    public function hasIdentity(AuthIdentityInterface $identity)
    {
        $ids = $this->getIdentities();
        foreach($ids as $id) {
            if($id->isEqual($identity)) {
                return true;
            }
        }
        
        return false;
    }
    
}