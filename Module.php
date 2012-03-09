<?php

namespace KapitchiIdentity;

use Zend\Module\Manager,
    Zend\EventManager\StaticEventManager,
    Zend\Module\Consumer\AutoloaderProvider,
    Zend\EventManager\EventDescription as Event,
    Zend\Mvc\MvcEvent as MvcEvent;

class Module implements AutoloaderProvider
{
    public function init(Manager $moduleManager)
    {
        $events = StaticEventManager::getInstance();
        $events->attach('bootstrap', 'bootstrap', array($this, 'initBootstrap'));
    }
    
    public function initBootstrap($e) {
        $app          = $e->getParam('application');
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
        $events->attach('KapitchiIdentity\Controller\AuthController', 'authenticate.init', function(Event $e) use ($locator) {
            $acl = $locator->get('KapitchiIdentity\Service\Acl');
        });
        
        $events = StaticEventManager::getInstance();
        $events->attach('KapitchiIdentity\Service\Acl', 'loadResource', function(Event $e) {
            $acl = $e->getParam('acl');
            $resource = $e->getParam('resource');
            
            //XXX this allows everything for user account
            $acl->addResource($resource);
            $acl->allow('user', $resource, null);
        });
    }
    
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
}
