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
        $instance = $this;
        
        //register auth strategies
        $events->attach('KapitchiIdentity\Controller\AuthController', 'login.pre', function($e) use ($locator, $instance) {
            $strategies = $instance->getOption('auth.strategies', array());
            $controller = $e->getTarget();
            foreach($strategies as $strategyDi => $enabled) {
                if($enabled) {
                    $strategy = $locator->get($strategyDi);
                    if(!$strategy instanceof AuthStrategy\Strategy) {
                        throw RuntimeException(get_class($strategy) . " is not auth strategy");
                    }
                    $controller->events()->attachAggregate($strategy);
                    $strategy->onLoginPre($e);
                }
            }
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
        
        //role identity form extensioin
        $events->attach('di', 'newInstance', function($e) use($locator) {
            $instance = $e->getParam('instance');
            if($instance instanceof \KapitchiIdentity\Form\Identity) {
                $instance->addExtSubForm($locator->get('KapitchiIdentity\Form\IdentityRole'), 'KapitchiIdentity_IdentityRole');
            }
        });
        
        //plugins
        if($this->getOption('plugins.ZfcAcl', true)) {
            $plugin = $locator->get('KapitchiIdentity\Plugin\ZfcAcl');
            $plugin->bootstrap();
        }
            
    }
    
    public function getDir() {
        return __DIR__;
    }
    
    public function getNamespace() {
        return __NAMESPACE__;
    }
    
}
