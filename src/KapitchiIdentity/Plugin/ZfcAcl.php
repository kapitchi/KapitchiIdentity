<?php

namespace KapitchiIdentity\Plugin;

use Zend\EventManager\StaticEventManager;

class ZfcAcl {
    protected $aclService;
    protected $identityRoleService;
    
    public function bootstrap() {
        $events = StaticEventManager::getInstance();
        $instance = $this;
        $events->attach('KapitchiIdentity\Service\Auth', array('authenticate.valid', 'clearIdentity.post'), function($e) use($instance) {
            $instance->getAclService()->invalidateCache();
        });
        
        $events->attach('ZfcAcl\Service\Acl', 'getRole', function($e) use($instance) {
            $service = $instance->getIdentityRoleService();
            return $service->getCurrentRole();
        });
    }
    
    public function getAclService() {
        return $this->aclService;
    }

    public function setAclService($aclService) {
        $this->aclService = $aclService;
    }

    public function getIdentityRoleService() {
        return $this->identityRoleService;
    }

    public function setIdentityRoleService($identityRoleService) {
        $this->identityRoleService = $identityRoleService;
    }



}