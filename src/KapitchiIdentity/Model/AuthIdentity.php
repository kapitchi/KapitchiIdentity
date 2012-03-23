<?php

namespace KapitchiIdentity\Model;

use Zend\Acl\Role,
    ZfcBase\Model\ModelAbstract,
    InvalidArgumentException;

class AuthIdentity extends ModelAbstract implements Role {
    protected $identity;
    protected $roleId;
    protected $localIdentityId;
    
    public function __construct($identity, $roleId, $localIdentityId = null) {
        $this->setIdentity($identity);
        $this->setRoleId($roleId);
        
        if($localIdentityId !== null) {
            $this->setLocalIdentityId($localIdentityId);
        }
    }
    
    public function setRoleId($roleId) {
        if(empty($roleId)) {
            throw InvalidArgumentException("Role ID must be a non empty string");
        }
        $this->roleId = $roleId;
    }
    
    public function getRoleId() {
        return $this->roleId;
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
    
//    public function serialize() {
//        return serialize($this->toArray());
//    }
//    
//    public function unserialize($data) {
//        var_dump($data);
//        exit;
//        $this->fromArray($data);
//    }
    
    public function __toString() {
        return $this->getIdentity() . ' [' . $this->getRoleId() . ']';
    }
}