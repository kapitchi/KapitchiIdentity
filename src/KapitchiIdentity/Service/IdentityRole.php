<?php

namespace KapitchiIdentity\Service;

use Zend\Acl\Role\GenericRole,
    ZfcBase\Service\ModelServiceAbstract,
    KapitchiIdentity\Model\AuthIdentity,
    KapitchiIdentity\Model\IdentityRole as IdentityRoleModel;
        
class IdentityRole extends ModelServiceAbstract {
    protected $authService;
    protected $currentRole;
    protected $currentStaticRole;
    
    public function getCurrentStaticRole() {
        //TODO is it safe to save? :)
        //if($this->currentRole === null) {
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
            
            try {
                $role = $this->get(array(
                    'identityId' => $localIdentityId
                ));
            } catch(\Exception $e) {
                //throw new \Exception("I can't find current role!", 0, $e);
                //do we still have an identity in the DB?
                $this->getAuthService()->clearIdentity();
                $role = new GenericRole('guest');
            }
            
            $this->currentRole = $role;
        //}
        
        return $this->currentRole;
    }
    
    public function getCurrentRole() {
        $role = $this->getCurrentStaticRole();
        if($role instanceof IdentityRoleModel) {
            $identityRole = new GenericRole('identity/' . $role->getIdentityId());
            return $identityRole;
        }
        
        return $role;
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