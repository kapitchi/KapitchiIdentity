<?php

namespace KapitchiIdentity\Controller;

use Zend\Mvc\Controller\ActionController as ZendActionController;

class RegistrationController  extends ZendActionController {
    protected $registrationService;
    protected $registrationForm;
    
    public function registerAction() {
        $form = $this->getRegistrationForm();
        
        $request = $this->getRequest();

        $form->populate(array(
            'requestIp' => $request->server()->get('REMOTE_ADDR')
        ));
        
        if($request->isPost()) {
            $postData = $request->post()->toArray();
            if($form->isValid($postData)) {
                $params = $this->getRegistrationService()->register($form->getValues());
                $res = $this->events()->trigger('register.post', $this, $params, function($ret) {
                    return $ret instanceof Response;
                });
                $result = $res->last();
                if($result instanceof Response) {
                    return $result;
                }
            }
        }
        
        $form->addElement('submit', 'submit', array(
            'label' => 'Register'
        ));
        
        return array(
            'registrationForm' => $form,
        );
    }
    
    //listeners
    protected function attachDefaultListeners()
    {
        parent::attachDefaultListeners();
        $instance = $this;
        $events = $this->events();
        $events->attach('register.post', function($e) use($instance) {
            return $instance->redirect()->toRoute('KapitchiIdentity/Auth/Login');
        });
    }

    //getters/setters
    public function getRegistrationService() {
        return $this->registrationService;
    }

    public function setRegistrationService($registrationService) {
        $this->registrationService = $registrationService;
    }

    public function getRegistrationForm() {
        return $this->registrationForm;
    }

    public function setRegistrationForm($registrationForm) {
        $this->registrationForm = $registrationForm;
    }

    
}