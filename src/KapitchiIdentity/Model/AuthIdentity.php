<?php

namespace KapitchiIdentity\Model;

use ZfcBase\Model\ModelAbstract,
    InvalidArgumentException;

class AuthIdentity extends ModelAbstract {
    protected $identity;
    protected $localIdentityId;
    protected $roleId;
    
    public function __construct($identity, $localIdentityId = null, $roleId = null) {
        $this->setIdentity($identity);
        if($localIdentityId !== null) {
            $this->setLocalIdentityId($localIdentityId);
        }
        if($roleId !== null) {
            $this->setRoleId($roleId);
        }
    }
    
    public function getRoleId() {
        return $this->roleId;
    }

    public function setRoleId($roleId) {
        $this->roleId = $roleId;
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