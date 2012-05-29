<?php

namespace KapitchiIdentity\Plugin;

use Zend\Mvc\ApplicationInterface,
    KapitchiBase\Module\Plugin\PluginAbstract;

class RegistrationCaptcha extends PluginAbstract {
    public function bootstrap(ApplicationInterface $application) {
        $events = $application->events()->getSharedManager();
        $locator = $application->getLocator();
        $instance = $this;
        
        $events->attach('KapitchiIdentity\Form\Registration', 'construct.post', function($e) use ($instance, $locator) {
            $form = $e->getTarget();
            $addElement = true;
            
            $broker = $instance->getModule()->getBroker();
            if($broker->isPluginBootstraped('AuthCredentialEmailValidation')) {
                $emailPlugin = $broker->load('AuthCredentialEmailValidation');
                $stage = $emailPlugin->getStage();
                if($stage == 'validated' || $stage == 'registration') {
                    $addElement = false;
                }
            }
            
            if($addElement) {
                $form->addElement('Captcha', 'captcha', $instance->getOption('captcha_element_options'));
            }
        });
    }
}