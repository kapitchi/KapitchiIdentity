<?php

namespace KapitchiIdentity\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Identity extends AbstractHelper
{
    protected $authService;
    protected $identityService;
    
//    public function __invoke()
//    {
//        throw new \Exception('N/I');
//    }
    
    public function getDisplayName() {
        $authService = $this->getAuthService();
        if($authService->hasIdentity()) {
            $localId = $authService->getLocalIdentityId();
            $model = $this->getModelById($localId);
            $entity = $model->getEntity();
            return $entity->getDisplayName();
        }
        
        return '';
    }
    
    public function hasIdentity() {
        $authService = $this->getAuthService();
        return $authService->hasIdentity();
    }
    
    public function getIdentityById($id) {
        $identity = $this->getIdentityService()->find($id);
        if(!$identity) {
            throw new \Exception("No identity [$id]");
        }
        return $identity;
    }
    
    public function getModelById($id) {
        $identity = $this->getIdentityById($id);
        return $this->getIdentityService()->loadModel($identity);
    }
    
    public function getAuthService()
    {
        return $this->authService;
    }

    public function setAuthService($authService)
    {
        $this->authService = $authService;
    }

    public function getIdentityService()
    {
        return $this->identityService;
    }

    public function setIdentityService($identityService)
    {
        $this->identityService = $identityService;
    }

}