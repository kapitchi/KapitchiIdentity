<?php

namespace KapitchiIdentity\Service;

use     Zend\Acl\Role\GenericRole,
        KapitchiBase\Service\ModelServiceAbstract,
        KapitchiIdentity\Model\AuthIdentity;
        
class IdentityRole extends ModelServiceAbstract {
    protected $authService;
    protected $currentRole;
    
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
            
            $role = $this->get(array(
                'identityId' => $localIdentityId
            ));
            if(!$role) {
                throw new \Exception("I can't find current role!");
            }
            $this->currentRole = $role;
        }
        
        return $this->currentRole;
    }
    
    protected function attachDefaultListeners() {
        parent::attachDefaultListeners();
        
        $mapper = $this->getMapper();
        $this->events()->attach('get.load', function($e) use ($mapper){
            $filter = $e->getParam('identityId');
            if(!$filter) {
                return;
            }
            return $mapper->findByIdentityId($filter);
        });
    }
    
    //getters/setters
    public function getAuthService() {
        return $this->authService;
    }

    public function setAuthService($authService) {
        $this->authService = $authService;
    }
    
}