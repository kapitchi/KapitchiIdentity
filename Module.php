<?php

namespace KapitchiIdentity;

use Zend\Module\Manager,
    Zend\Mvc\AppContext as Application,
    Zend\EventManager\StaticEventManager,
    Zend\EventManager\EventDescription as Event,
    Zend\Mvc\MvcEvent as MvcEvent,
    KapitchiBase\Module\ModuleAbstract,
    KapitchiIdentity\Service\Acl as AclService;

class Module extends ModuleAbstract {
    
    public function bootstrap(Manager $moduleManager, Application $app) {
        $locator      = $app->getLocator();
        
        $events = StaticEventManager::getInstance();
        
        //route protector
        if($this->getOption('acl.enable_route_protector', true)) {
            $routeProtector = $locator->get('KapitchiIdentity\Service\Acl\ProtectorRoute');
            $app->events()->attach('dispatch', array($routeProtector, 'dispatch'), 1000);
        }
        
        //register auth strategies
        $events->attach('KapitchiIdentity\Controller\AuthController', 'authenticate.init',
                array($locator->get('KapitchiIdentity\Service\Auth\Credential'), 'onInit'));
        
        //acl-auth
        $events->attach('KapitchiIdentity\Service\Auth', 'clearIdentity.post', function($e) use($locator) {
            $acl = $locator->get('KapitchiIdentity\Service\Acl');
            $acl->invalidateCache();
        });
        
        //auth-identity
        $events->attach('KapitchiIdentity\Service\Identity', 'persist.pre', function($e) use($locator) {
            $service = $locator->get('KapitchiIdentity\Service\Auth');
            $identity = $e->getParam('model');
            
            $id = $service->getLocalIdentityId();
            if($id !== null) {
                $identity->setOwnerId($id);
            }
        });
        
        //auth-acl
        $events->attach('KapitchiIdentity\Service\Acl', 'getRole', function($e) use($locator) {
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
