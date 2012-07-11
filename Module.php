<?php

namespace KapitchiIdentity;

use Zend\EventManager\Event,
    KapitchiBase\ModuleManager\AbstractModule;

class Module extends AbstractModule {
    
    public function onBootstrap(Event $e) {
//        $sm      = $app->getServiceManager();
//        $events = $app->events()->getSharedManager();
//        $instance = $this;
//        
//        //auth-identity
//        $events->attach('KapitchiIdentity\Service\Identity', 'persist.pre', function($e) use($sm) {
//            $service = $sm->get('KapitchiIdentity\Service\Auth');
//            $identity = $e->getParam('model');
//            
//            try {
//                $id = $service->getLocalIdentityId();
//                if($id !== null) {
//                    $identity->setOwnerId($id);
//                }
//            //user might not be logged in
//            } catch(\Exception $e) {
//                
//            }
//        });
        
        
    }
    
    public function getDir() {
        return __DIR__;
    }
    
    public function getNamespace() {
        return __NAMESPACE__;
    }
}