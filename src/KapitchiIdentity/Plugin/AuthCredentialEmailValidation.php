<?php

namespace KapitchiIdentity\Plugin;

use Zend\EventManager\StaticEventManager,
    Zend\Mvc\AppContext as Application,
    KapitchiBase\Plugin\PluginAbstract;

class AuthCredentialEmailValidation extends PluginAbstract {
    protected $stage = 'registration';
    
    protected function bootstrap(Application $application) {
        $locator = $application->getLocator();
        $instance = $this;
        
        $events = StaticEventManager::getInstance();
        
        $events->attach('KapitchiIdentity\Controller\RegistrationController', 'register.pre', function($e) use($instance, $locator) {
            $request = $e->getParam('request');
            $regId = $request->query()->get('regid');
            $form = $e->getParam('viewModel')->registrationForm;
            $authForm = $form->getExtSubForm('AuthCredential');
                
            if(!empty($regId)) {
                $token = $request->query()->get('token');
            
                $service = $locator->get('KapitchiIdentity\Service\Registration');
                $reg = $service->get(array(
                    'priKey' => $regId
                ));

                $data = $reg->getData();
                if(empty($data['token']) || $data['token'] != $token) {
                    throw new \Exception("Tokens do not match");
                }

                $form->populate($data['formData']);
                $username = $authForm->getElement('username');
                $username->setAttrib('readonly', true);
                $username->setDescription('You email address has been validated, please proceed with registration');

                $instance->setStage('validated');
            }
            else {
                $el = $authForm->getElement('password');
                $el->setAttrib('readonly', true);
                $el->setDescription('You will be asked for password once you validate an email address provided');
                $authForm->removeElement('passwordConfirm');
            }
        });
        
        $events->attach('KapitchiIdentity\Controller\RegistrationController', 'register.post', function($e) use($instance, $locator) {
            if($instance->getStage() == 'triggerValidation') {
                $viewModel = $e->getParam('viewModel');
                $form = $viewModel->registrationForm;
                $authForm = $form->getExtSubForm('AuthCredential');
            
                $e->stopPropagation();
            }
        }, 100);
        
        $events->attach('KapitchiIdentity\Service\Registration', 'persist.pre', function($e) use($instance, $locator) {
            if($instance->getStage() == 'registration') {
                $model = $e->getParam('model');
                $formData = $e->getParam('data');
                $data = array(
                    'formData' => $formData,
                    'token' => md5(uniqid()),
                );

                if(empty($formData['exts']['AuthCredential']['username'])) {
                    throw new \Exception("Username/email is not set???!!!");
                }
                $model->setData($data);
            }
        });
        
        $events->attach('KapitchiIdentity\Service\Registration', 'persist.post', function($e) use($instance, $locator) {
            if($instance->getStage() == 'registration') {
                $model = $e->getParam('model');

                $data = $model->getData();
                
                $email = $data['formData']['exts']['AuthCredential']['username'];
                $token = $data['token'];
                $regId = $model->getId();

                //SEND email
                $msg = new \Zend\Mail\Message();
                $msg->addTo($email);
                $msg->setBody("/KapitchiIdentity/registration/register?regid=$regId&token=$token");
                
                $transport = new \Zend\Mail\Transport\Sendmail();
                $transport->send($msg);
                
                $instance->setStage('triggerValidation');
                
                //do not persist extension data!
                $e->stopPropagation();
            }
        }, 100);
        
    }
    
    public function getStage() {
        return $this->stage;
    }

    public function setStage($stage) {
        $this->stage = $stage;
    }
        
}