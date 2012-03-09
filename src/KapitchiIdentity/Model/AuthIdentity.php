<?php

namespace KapitchiIdentity\Model;

use Zend\Acl\Role,
    ZfcBase\Model\ModelAbstract;

class AuthIdentity extends ModelAbstract implements Role {
    protected $identity;
    protected $roleId;
    protected $localIdentityId;
    
    public function __construct($identity, $roleId, $localIdentityId = null) {
        $this->setRoleId($roleId);
        
        $this->identity = $identity;
        $this->localIdentityId = $localIdentityId;
    }
    
    public function setRoleId($roleId) {
        $this->roleId = $roleId;
    }
    
    public function getRoleId() {
        return $this->roleId;
    }
    
    public function getIdentity() {
        return $this->identity;
    }
    
    public function getLocalIdentityId() {
        return $this->localIdentityId;
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