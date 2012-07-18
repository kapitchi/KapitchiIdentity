<?php

namespace KapitchiIdentity\Entity;

use Zend\Acl\Role\RoleInterface;

class IdentityRole implements RoleInterface {
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