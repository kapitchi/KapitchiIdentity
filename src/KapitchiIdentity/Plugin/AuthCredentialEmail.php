<?php

namespace KapitchiIdentity\Plugin;

use Zend\Mvc\ApplicationInterface,
    KapitchiBase\Module\Plugin\PluginAbstract;

class AuthCredentialEmail extends PluginAbstract {
    
    public function bootstrap(ApplicationInterface $application) {
        $locator = $application->getLocator();
        $instance = $this;
        
        $events = $this->getSharedManager();
        
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