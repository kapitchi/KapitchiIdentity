<?php

namespace KapitchiIdentity;

use Zend\Module\Manager,
    Zend\Mvc\AppContext as Application,
    Zend\EventManager\StaticEventManager,
    Zend\EventManager\EventDescription as Event,
    Zend\Mvc\MvcEvent as MvcEvent,
    KapitchiBase\Module\ModuleAbstract;

class Module extends ModuleAbstract {
    
    public function getDir() {
        return __DIR__;
    }
    
    public function getNamespace() {
        return __NAMESPACE__;
    }

    public function bootstrap(Manager $moduleManager, Application $app) {
        $locator      = $app->getLocator();
        
        //route protector test
        /*$app->events()->attach('route', function(MvcEvent $e) use($locator) {
            $routeName = $e->getRouteMatch()->getMatchedRouteName();
            
            $aclService = $locator->get('KapitchiIdentity\Service\Acl');
            $aclService->invalidateCache();
            $ret = $aclService->isAllowed('route/' . $routeName);
            if(!$ret) {
                $e->setError('error-controller-cannot-dispatch');
            }
            
        }, -10);
        */
        
        $events = StaticEventManager::getInstance();
        
        $events->attach('KapitchiIdentity\Controller\AuthController', 'authenticate.init',
                array($locator->get('KapitchiIdentity\Service\Auth\Credential'), 'onInit'));
        
        $events->attach('KapitchiIdentity\Service\Auth', 'clearIdentity.post', function($e) use($locator) {
            $acl = $locator->get('KapitchiIdentity\Service\Acl');
            $acl->invalidateCache();
        });
        
        $events->attach('KapitchiIdentity\Service\Acl', 'getRole', function($e) use($locator) {
            $authService = $locator->get('KapitchiIdentity\Service\Auth');
            if(!$authService->hasIdentity()) {
                return;
            }

            $authIdentity = $authService->getIdentity();
//            $roleId = $authIdentity->getRoleId();
//            if(empty($roleId)) {
//                throw new \Exception("User has got no role, why???");
//            }

            return $authIdentity;
        });
            
        $events->attach('KapitchiIdentity\Controller\AuthController', 'authenticate.init', function(Event $e) use ($locator) {
            $acl = $locator->get('KapitchiIdentity\Service\Acl');
        });
        
        $events->attach('KapitchiIdentity\Service\Acl', 'loadResource', function(Event $e) {
            $acl = $e->getParam('acl');
            $resource = $e->getParam('resource');
            
            //XXX this allows everything for user account
            $acl->addResource($resource);
            $acl->allow('user', $resource, null);
        });
    }
    
}
