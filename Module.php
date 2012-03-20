<?php

namespace KapitchiIdentity;

use Zend\Module\Manager,
    Zend\Mvc\AppContext as Application,
    Zend\EventManager\StaticEventManager,
    Zend\EventManager\EventDescription as Event,
    Zend\Mvc\MvcEvent as MvcEvent,
    KapitchiBase\Module\ModuleAbstract;

class Module extends ModuleAbstract {
    
    public function bootstrap(Manager $moduleManager, Application $app) {
        $locator      = $app->getLocator();
        
        $events = StaticEventManager::getInstance();
        
        //register auth strategies
        $events->attach('KapitchiIdentity\Controller\AuthController', 'authenticate.init',
                array($locator->get('KapitchiIdentity\Service\Auth\Credential'), 'onInit'));
        
        //auth-identity
        $events->attach('KapitchiIdentity\Service\Identity', 'persist.pre', function($e) use($locator) {
            $service = $locator->get('KapitchiIdentity\Service\Auth');
            $identity = $e->getParam('model');
            
            $id = $service->getLocalIdentityId();
            if($id !== null) {
                $identity->setOwnerId($id);
            }
        });
        
        //acl
        $events->attach('KapitchiIdentity\Service\Auth', 'clearIdentity.post', function($e) use($locator) {
            $acl = $locator->get('KapitchiAcl\Service\Acl');
            $acl->invalidateCache();
        });
        
        $events->attach('KapitchiAcl\Service\Acl', 'getRole', function($e) use($locator) {
            $authService = $locator->get('KapitchiIdentity\Service\Auth');
            if(!$authService->hasIdentity()) {
                return;
            }

            $authIdentity = $authService->getIdentity();
            return $authIdentity;
        });
            
    }
    
    public function getDir() {
        return __DIR__;
    }
    
    public function getNamespace() {
        return __NAMESPACE__;
    }
    
}
