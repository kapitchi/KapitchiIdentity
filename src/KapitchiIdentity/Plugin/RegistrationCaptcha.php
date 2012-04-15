<?php

namespace KapitchiIdentity\Plugin;

use Zend\Mvc\AppContext as Application,
    Zend\EventManager\StaticEventManager,
    ZfcBase\Model\ModelAbstract,
    KapitchiBase\Module\Plugin\PluginAbstract,
    KapitchiIdentity\Model\AuthIdentity;

class RegistrationCaptcha extends PluginAbstract {
    public function bootstrap(Application $application) {
        $events = StaticEventManager::getInstance();
        $locator = $application->getLocator();
        $instance = $this;
        
        $events->attach('KapitchiIdentity\Form\Registration', 'construct.post', function($e) use ($instance, $locator) {
            $form = $e->getTarget();
            
            //TODO we really need some plugin manager
            $emailPlugin = $locator->get('KapitchiIdentity\Plugin\AuthCredentialEmailValidation');
            
            if($emailPlugin->getStage() != 'validated') {
                $form->addElement('Captcha', 'captcha', $instance->getOption('captcha_element_options'));
            }
        });
        
    }
}