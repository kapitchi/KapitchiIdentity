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
        
        
        $events->attach('KapitchiIdentity\Service\Identity', array('get.exts', 'get.ext.KapitchiIdentity_IdentityRole'), function($e) use($locator) {
            $identity = $e->getParam('model');
            $identityRoleService = $locator->get('KapitchiIdentity\Service\IdentityRole');
            $model = $identityRoleService->get(array(
                'identityId' => $identity->getId()
            ));

            if($model) {
                $identity->ext('KapitchiIdentity_IdentityRole', $model);
            }
        
        });
        
        $events->attach('KapitchiIdentity\Service\Identity', array('persist.post'), function($e) use($locator) {
            $data = $e->getParam('data');
            if(!empty($data['exts']['KapitchiIdentity_IdentityRole'])) {
                $identity = $e->getParam('model');
                $service = $locator->get('KapitchiIdentity\Service\IdentityRole');
                $modelData = $data['exts']['KapitchiIdentity_IdentityRole'];
                $modelData['identityId'] = $identity->getId();
                $ret = $service->persist($modelData);
                $model = $ret['model'];

                $identity->ext('KapitchiIdentity_IdentityRole', $model);
            }
        });
        
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
