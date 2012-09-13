<?php

namespace KapitchiIdentity\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Identity extends AbstractHelper
{
    protected $identityService;
    
    public function getDisplayName($id) {
        $identity = $this->getIdentityService()->find($id);
        if(!$identity) {
            throw new \Exception("No identity [$id]");
        }
        return $identity->getDisplayName();
    }
    
    public function find($id) {
        $identity = $this->getIdentityService()->find($id);
        return $identity;
    }
    
    public function loadModel($id) {
        $identity = $this->find($id);
        if(!$identity) {
            throw new \Exception("No identity [$id]");
        }
        return $this->getIdentityService()->loadModel($identity);
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