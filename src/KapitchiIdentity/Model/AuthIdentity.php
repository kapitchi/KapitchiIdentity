<?php
namespace KapitchiIdentity\Model;

class AuthIdentity implements AuthIdentityInterface
{
    protected $identity;
    protected $id;
    
    public function __construct($identity, $id = null) {
        $this->setIdentity($identity);
        if($id !== null) {
            $this->setId($id);
        }
    }
    
    public function getIdentity() {
        return $this->identity;
    }

    public function setIdentity($identity) {
        $this->identity = $identity;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($localIdentityId) {
        $this->id = $localIdentityId;
    }
    
    public function isEqual(AuthIdentityInterface $identity)
    {
        if($this->getId() == $identity->getId()) {
            return true;
        }
        
        return false;
    }
    
    public function __toString() {
        return $this->getIdentity() . ' [' . $this->getId() . ']';
    }
}