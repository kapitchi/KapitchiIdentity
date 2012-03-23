<?php

namespace KapitchiIdentity\Model;

use ZfcBase\Model\ModelAbstract,
    Zend\Acl\Role;

class IdentityRole extends ModelAbstract implements Role {
    protected $roleId;
    protected $identityId;
    
    public function getRoleId() {
        return $this->roleId;
    }

    public function setRoleId($roleId) {
        $this->roleId = $roleId;
    }

    public function getIdentityId() {
        return $this->identityId;
    }

    public function setIdentityId($identityId) {
        $this->identityId = $identityId;
    }

}