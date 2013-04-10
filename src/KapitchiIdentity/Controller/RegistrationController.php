<?php
/**
 * Kapitchi Zend Framework 2 Modules (http://kapitchi.com/)
 *
 * @copyright Copyright (c) 2012-2013 Kapitchi Open Source Team (http://kapitchi.com/open-source-team)
 * @license   http://opensource.org/licenses/LGPL-3.0 LGPL 3.0
 */

namespace KapitchiIdentity\Controller;

use Zend\Mvc\Controller\ActionController as ZendActionController;

class RegistrationController  extends ZendActionController {
    protected $registrationService;
    protected $registrationForm;
    protected $registerViewModel;
    
    public function registerAction() {
        $form = $this->getRegistrationForm();
        $request = $this->getRequest();
        
        $viewModel = $this->getRegisterViewModel();
        $viewModel->registrationForm = $form;
        
        //TODO
        $form->populate(array(
            'requestIp' => $request->server()->get('REMOTE_ADDR')
        ));
        
        $params = array(
            'request' => $request,
            'response' => $this->getResponse(),
            'viewModel' => $viewModel,
        );
        
        $res = $this->events()->trigger('register.pre', $this, $params, function($ret) {
            return $ret instanceof Response;
        });
        $result = $res->last();
        if($result instanceof Response) {
            return $result;
        }
        
        if($request->isPost()) {
            $postData = $request->post()->toArray();
            if($form->isValid($postData)) {
                $registerResult = $this->getRegistrationService()->register($form->getValues());
                $params['registerResult'] = $registerResult;
                $res = $this->events()->trigger('register.post', $this, $params, function($ret) {
                    return $ret instanceof Response;
                });
                $result = $res->last();
                if($result instanceof Response) {
                    return $result;
                }
            }
        }
        
        //TODO
        $form->addElement('submit', 'submit', array(
            'label' => 'Register',
            'order' => 1000
        ));
        
        return $viewModel;
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

    public function getRegisterViewModel() {
        return $this->registerViewModel;
    }

    public function setRegisterViewModel($registerViewModel) {
        $this->registerViewModel = $registerViewModel;
    }

    
}