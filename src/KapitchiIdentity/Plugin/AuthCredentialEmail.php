<?php

namespace KapitchiIdentity\Plugin;

use Zend\EventManager\StaticEventManager,
    Zend\Mvc\AppContext as Application,
    KapitchiBase\Plugin\PluginAbstract;

class AuthCredentialEmail extends PluginAbstract {
    
    protected function bootstrap(Application $application) {
        $locator = $application->getLocator();
        $instance = $this;
        
        $events = StaticEventManager::getInstance();
        
        //registration
        $events->attach('KapitchiIdentity\Form\AuthCredential\Registration', 'construct.post', function($e) use($instance, $locator) {
            $form = $e->getTarget();
            $el = $form->getElement('username');
            $el->setLabel('Email');
            $el->addValidator('EmailAddress');
        });
        
        //login
        $events->attach('KapitchiIdentity\Form\AuthCredential\Login', 'construct.post', function($e) use($instance, $locator) {
            $form = $e->getTarget();
            $el = $form->getElement('username');
            $el->setLabel('Email');
            $el->addValidator('EmailAddress');
        });
        
    }
}