<?php

namespace KapitchiIdentityAcl\Service;

use ZfcAcl\Service\Acl\RoleProvider as RoleProviderInterface;

class RoleProvider implements RoleProviderInterface {
    protected $identityRoleService;
    
    public function getCurrentRole() {
        return $this->getIdentityRoleService()->getCurrentRole();
    }
    
    public function getIdentityRoleService() {
        return $this->identityRoleService;
    }

    public function setIdentityRoleService($identityRoleService) {
        $this->identityRoleService = $identityRoleService;
    }

}