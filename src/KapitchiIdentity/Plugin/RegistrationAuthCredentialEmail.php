<?php

namespace KapitchiIdentity\Plugin;

use Zend\EventManager\StaticEventManager,
    Zend\Mvc\AppContext as Application,
    KapitchiBase\Plugin\PluginAbstract;

class RegistrationAuthCredentialEmail extends PluginAbstract {
    public $extName = 'AuthCredential';
    
    protected function bootstrap(Application $application) {
        $locator = $application->getLocator();
        $instance = $this;
        
        $events = StaticEventManager::getInstance();
        $events->attach('KapitchiIdentity\Form\AuthCredential\Registration', 'construct.post', function($e) use($instance, $locator) {
            $form = $e->getTarget();
            $el = $form->getElement('username');
            $el->setLabel('Email');
            $el->addValidator('EmailAddress');
        });
        
        $events->attach('KapitchiIdentity\Service\Registration', 'persist.pre', function($e) use($instance, $locator) {
            $model = $e->getParam('model');
            $data = $e->getParam('data');
            $model->setData($data);
        });
        
        $events->attach('KapitchiIdentity\Service\Registration', 'persist.post', function($e) use($instance, $locator) {
            
            $e->stopPropagation();
        }, 100);
        
    }
}