<?php
namespace KapitchiIdentity\Model;

class AuthIdentity implements AuthIdentityInterface
{
    protected $identity;
    protected $localIdentityId;
    
    public function __construct($identity, $localIdentityId = null) {
        $this->setIdentity($identity);
        if($localIdentityId !== null) {
            $this->setLocalIdentityId($localIdentityId);
        }
    }
    
    public function getIdentity() {
        return $this->identity;
    }

    public function setIdentity($identity) {
        $this->identity = $identity;
    }

    public function getLocalIdentityId() {
        return $this->localIdentityId;
    }

    public function setLocalIdentityId($localIdentityId) {
        $this->localIdentityId = $localIdentityId;
    }
    
    public function __toString() {
        return $this->getIdentity() . ' [' . $this->getLocalIdentityId() . ']';
    }
}