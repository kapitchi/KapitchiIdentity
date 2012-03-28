<?php

namespace KapitchiIdentity\Plugin;

use Zend\EventManager\StaticEventManager,
    Zend\Mvc\AppContext as Application;

class ZfcAcl extends \KapitchiBase\Plugin\PluginAbstract {
    protected $aclService;
    protected $identityRoleService;
    
    protected function bootstrap(Application $application) {
        $events = StaticEventManager::getInstance();
        
        $aclService = $application->getLocator()->get('ZfcAcl\Service\Acl');
        $identityRoleService = $application->getLocator()->get('KapitchiIdentity\Service\IdentityRole');
        
        $events->attach('KapitchiIdentity\Service\Auth', array('authenticate.valid', 'clearIdentity.post'), function($e) use($aclService) {
            $aclService->invalidateCache();
        });
        
        $events->attach('ZfcAcl\Service\Acl', 'getRole', function($e) use($identityRoleService) {
            return $identityRoleService->getCurrentRole();
        });
    }
    
}