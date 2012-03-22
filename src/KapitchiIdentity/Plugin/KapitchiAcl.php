<?php

namespace KapitchiIdentity\Plugin;

class KapitchiAcl {
    protected $aclService;
    protected $authService;
    
    public function bootstrap() {
        $events = \Zend\EventManager\StaticEventManager::getInstance();
        $instance = $this;
        $events->attach('KapitchiIdentity\Service\Auth', 'clearIdentity.post', function($e) use($instance) {
            $instance->getAclService()->invalideCache();
        });

        $events->attach('KapitchiAcl\Service\Acl', 'getRole', function($e) use($instance) {
            $authService = $instance->getAuthService();
            if(!$authService->hasIdentity()) {
                return;
            }

            $authIdentity = $authService->getIdentity();
            return $authIdentity;
        });
    }
    
    public function getAclService() {
        return $this->aclService;
    }

    public function setAclService($aclService) {
        $this->aclService = $aclService;
    }

    public function getAuthService() {
        return $this->authService;
    }

    public function setAuthService($authService) {
        $this->authService = $authService;
    }


}