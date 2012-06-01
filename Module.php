<?php

namespace KapitchiIdentity;

use Zend\ModuleManager\ModuleManager,
    Zend\Mvc\ApplicationInterface,
    Zend\EventManager\EventDescription as Event,
    Zend\Mvc\MvcEvent as MvcEvent,
    KapitchiBase\Module\ModuleAbstract;

class Module extends ModuleAbstract {
    
    public function bootstrap(ModuleManager $moduleManager, ApplicationInterface $app) {
        $sm      = $app->getServiceManager();
        
        $events = $app->events()->getSharedManager();
        $instance = $this;
        
        //auth-identity
        $events->attach('KapitchiIdentity\Service\Identity', 'persist.pre', function($e) use($sm) {
            $service = $sm->get('KapitchiIdentity\Service\Auth');
            $identity = $e->getParam('model');
            
            try {
                $id = $service->getLocalIdentityId();
                if($id !== null) {
                    $identity->setOwnerId($id);
                }
            //user might not be logged in
            } catch(\Exception $e) {
                
            }
        });
        
        
    }
    
    public function getDir() {
        return __DIR__;
    }
    
    public function getNamespace() {
        return __NAMESPACE__;
    }
    
    public function getBroker() 
    {
        $broker = parent::getBroker();
        $broker->getClassLoader()->addPrefixPath('KapitchiIdentityAcl\Plugin', $this->getDir() . '/src/KapitchiIdentityAcl/Plugin');
        
        return $this->broker;
    }
    
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                $this->getDir() . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    $this->getNamespace() => $this->getDir() . '/src/' . $this->getNamespace(),
                    //'KapitchiIdentityAcl' => $this->getDir() . '/src/KapitchiIdentityAcl',
                ),
            ),
        );
    }
}
