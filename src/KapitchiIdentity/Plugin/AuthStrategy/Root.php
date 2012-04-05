<?php

namespace KapitchiIdentity\Plugin\AuthStrategy;

use Zend\Authentication\Result,
    Zend\Form\Form,
    KapitchiIdentity\Service\AuthIdentityResolver,
    KapitchiIdentity\Model\AuthIdentity;

class Root extends StrategyAbstract implements AuthIdentityResolver {
    protected $extName = 'Root';
    protected $rootLoginForm;
    
    public function loginPre() {
        $password = $this->getOption('password');
        $form = $this->getRootLoginForm();
        $this->getLoginForm()->addExtSubForm($form, $this->extName);
    }
    
    protected function loginAuth() {
        $request = $this->getRequest();
        if($request->isPost()) {
            $postData = $request->post()->toArray();
            if(!isset($postData['exts'][$this->extName])) {
                return;
            }
            
            $formData = $postData['exts'][$this->extName];
            $form = $this->getRootLoginForm();
            if($form->isValid($formData)) {
                $values = $form->getValues();
                $vals = $values[$this->extName];
                $rootLogin = $vals['rootLogin'];
                $password = $vals['password'];
                if($rootLogin) {
                    if($this->getOption('password') == md5($password)) {
                        return $this;
                    }
                    else {
                        $form->getElement('password')->addError('Incorrect password provided');
                    }
                }
            }
        }
    }
    
    public function resolveAuthIdentity($id) {
        return new AuthIdentity($id, 1, 'root');
    }
    
    public function authenticate() {
        return new Result(Result::SUCCESS, 'root');
    }
    
    protected function getRootLoginForm() {
        if($this->rootLoginForm === null) {
            $form = new \Zend\Form\Form();
            $form->addElement('checkbox', 'rootLogin', array(
                'label' => 'Login as root',
                'description' => 'This gives you root privileges - you are allowed to do everything on anything',
            ));
            $form->addElement('password', 'password', array(
                'label' => 'Root password',
                'required' => true,
            ));
            
            $this->rootLoginForm = $form;
        }
        
        return $this->rootLoginForm;
        
    }
}