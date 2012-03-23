<?php

namespace KapitchiIdentity\Service;

use     Zend\Acl\Role\GenericRole,
        ZfcBase\Service\ServiceAbstract,
        KapitchiIdentity\Model\AuthIdentity;
        
class Role extends ServiceAbstract {
    protected $authService;
    protected $currentRole;
    protected $identityRoleMapper;
    
    public function getCurrentRole() {
        if($this->currentRole === null) {
            $authService = $this->getAuthService();
            
            //not authenticated user
            if(!$authService->hasIdentity()) {
                $this->currentRole = new GenericRole('guest');
                return $this->currentRole;
            }

            $id = $authService->getIdentity();
            
            $localIdentityId = $id->getLocalIdentityId();
            if(empty($localIdentityId)) {
                //some strategies might provide roleId also.
                $roleId = $id->getRoleId();
                if(empty($roleId)) {
                    $roleId = 'auth';
                }
                $this->currentRole = new GenericRole($roleId);
                return $this->currentRole;
            }
            
            $role = $this->getByIdentityId($localIdentityId);
            if(!$role) {
                throw new \Exception("I can't find current role!");
            }
            $this->currentRole = $role;
        }
        
        return $this->currentRole;
    }
    
    public function getByIdentityId($identityId) {
        return $this->getIdentityRoleMapper()->findByIdentityId($identityId);
    }
    
    //getters/setters
    public function getAuthService() {
        return $this->authService;
    }

    public function setAuthService($authService) {
        $this->authService = $authService;
    }
    
    public function getIdentityRoleMapper() {
        return $this->identityRoleMapper;
    }

    public function setIdentityRoleMapper($identityRoleMapper) {
        $this->identityRoleMapper = $identityRoleMapper;
    }



}