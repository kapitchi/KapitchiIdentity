<?php

namespace KapitchiIdentity\Plugin;

class ZfcAcl {
    protected $aclService;
    protected $roleService;
    
    public function bootstrap() {
        $events = \Zend\EventManager\StaticEventManager::getInstance();
        $instance = $this;
        $events->attach('KapitchiIdentity\Service\Auth', array('authenticate.valid', 'clearIdentity.post'), function($e) use($instance) {
            $instance->getAclService()->invalidateCache();
        });
        
        $events->attach('ZfcAcl\Service\Acl', 'getRole', function($e) use($instance) {
            $roleService = $instance->getRoleService();
            return $roleService->getCurrentRole();
        });
    }
    
    public function getAclService() {
        return $this->aclService;
    }

    public function setAclService($aclService) {
        $this->aclService = $aclService;
    }

    public function getRoleService() {
        return $this->roleService;
    }

    public function setRoleService($roleService) {
        $this->roleService = $roleService;
    }



}