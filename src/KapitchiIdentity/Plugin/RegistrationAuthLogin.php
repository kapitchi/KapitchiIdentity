<?php

namespace KapitchiIdentity\Plugin;

use Zend\Mvc\AppContext as Application,
    Zend\EventManager\StaticEventManager,
    ZfcBase\Model\ModelAbstract,
    KapitchiBase\Plugin\PluginAbstract,
    KapitchiIdentity\Model\AuthIdentity;

class RegistrationAuthLogin extends PluginAbstract {
    protected function bootstrap(Application $application) {
        $events = StaticEventManager::getInstance();
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