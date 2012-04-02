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
        $authService = $application->getLocator()->get('KapitchiIdentity\Service\Auth');
        $identityRoleService = $application->getLocator()->get('KapitchiIdentity\Service\IdentityRole');
        
        $events->attach('KapitchiIdentity\Service\Auth', array('authenticate.valid', 'clearIdentity.post'), function($e) use($aclService) {
            $aclService->invalidateCache();
        });
        
        $events->attach('ZfcAcl\Service\Acl', 'getRole', function($e) use($identityRoleService) {
            return $identityRoleService->getCurrentRole();
        });
        
        $events->attach('ZfcAcl\Service\Acl', 'staticAclLoaded', function($e) use($identityRoleService) {
            $roleId = $e->getParam('roleId');
            $staticRole = $identityRoleService->getCurrentStaticRole();
            if($roleId != $staticRole->getRoleId()) {
                $acl = $e->getParam('acl');
                $acl->addRole($roleId, $staticRole);
            }
        });
        
        $events->attach('ZfcAcl\Service\Acl', 'loadResource', array(
            $application->getLocator()->get('KapitchiIdentity\Plugin\ZfcAcl\ResourceLoader'),
            'onLoadResource'
        ));
    }
    
}