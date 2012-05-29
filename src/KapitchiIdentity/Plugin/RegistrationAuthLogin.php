<?php

namespace KapitchiIdentity\Plugin;

use Zend\Mvc\ApplicationInterface,
    ZfcBase\Model\ModelAbstract,
    KapitchiBase\Module\Plugin\PluginAbstract,
    KapitchiIdentity\Model\AuthIdentity;

class RegistrationAuthLogin extends PluginAbstract {
    public function bootstrap(ApplicationInterface $application) {
        $events = $application->events()->getSharedManager();
        $locator = $application->getLocator();
        $instance = $this;
        
        $events->attach('KapitchiIdentity\Service\Registration', 'register.post', function($e) use ($locator) {
            $reg = $e->getParam('model');
            $identity = $reg->ext('Identity');
            if($identity) {
                $authIdentity = new AuthIdentity('', $identity->getId());
                $locator->get('KapitchiIdentity\Service\Auth')->setIdentity($authIdentity);
            }
        });
        
        $events->attach('KapitchiIdentity\Controller\RegistrationController', 'register.post', function($e) use($instance) {
            return $e->getTarget()->redirect()->toRoute($instance->getOption('redirect_route', 'KapitchiIdentity/Profile/Me'));
        });
    }
}