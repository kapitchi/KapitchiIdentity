<?php

namespace KapitchiIdentity\Plugin;

use Zend\EventManager\StaticEventManager,
    Zend\Mvc\ApplicationInterface,
    KapitchiBase\Module\Plugin\PluginAbstract;

class AuthCredentialForgotPassword extends PluginAbstract {
    
    protected function bootstrap(ApplicationInterface $application) {
        $locator = $application->getLocator();
        $instance = $this;
        
        $events = StaticEventManager::getInstance();
        
        $events->attach('KapitchiIdentity\Controller\AuthController', 'authenticate.post', function($e) use($instance, $locator) {
            $adapter = $e->getParam('adapter');
            if($adapter instanceof \KapitchiIdentity\AuthStrategy\Credential) {
                $result = $e->getParam('result');
                if($result->getCode() == \Zend\Authentication\Result::FAILURE_CREDENTIAL_INVALID) {
                    $viewModel = $e->getParam('viewModel');
                    $loginForm = $viewModel->loginForm;
                    $form = $loginForm->getExtSubForm('Credential');
                    $form->getElement('password')->setDescription('Have you forgotten your password? - this should link to "password recovery page"');
                }
            }
        });
        
    }
}