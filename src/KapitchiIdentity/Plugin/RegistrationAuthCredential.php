<?php

namespace KapitchiIdentity\Plugin;

use Zend\EventManager\StaticEventManager,
    Zend\Mvc\AppContext as Application,
    KapitchiBase\Plugin\PluginAbstract;

class RegistrationAuthCredential extends PluginAbstract {
    public $extName = 'KapitchiIdentity_AuthCredential';
    
    protected function bootstrap(Application $application) {
        $locator = $application->getLocator();
        $instance = $this;
        
        $events = StaticEventManager::getInstance();
        $events->attach('KapitchiIdentity\Form\Registration', 'construct.post', function($e) use($instance, $locator) {
            $form = $locator->get('KapitchiIdentity\Form\Auth\Registration');
            $e->getTarget()->addExtSubForm($form, $instance->extName);
        });
        
        $events->attach('KapitchiIdentity\Service\Registration', 'register.post', function($e) use($instance, $locator) {
            $data = $e->getParam('data');
            if(isset($data['exts'][$instance->extName])) {
                $extData = $data['exts'][$instance->extName];
                
                $model = $e->getParam('model');
                $authService = $locator->get('KapitchiIdentity\Service\AuthCredential');
                $mapper = $authService->getMapper();
                $passwordHash = $authService->getPasswordHash();
                
                $authCredential = \KapitchiIdentity\Model\AuthCredential::fromArray($extData);
                $authCredential->setPasswordHash($passwordHash->generateHash($extData['password']));
                $authCredential->setIdentityId($model->getIdentityId());
                
                $mapper->persist($authCredential);
                
                $model->ext('KapitchiIdentity_AuthCredential', $authCredential);
            }
        });
        
    }
}