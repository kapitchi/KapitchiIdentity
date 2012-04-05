<?php

namespace KapitchiIdentity\Plugin\AuthStrategy;

use Zend\Authentication\Result,
    Zend\Form\Form,
    KapitchiIdentity\Service\AuthIdentityResolver,
    KapitchiIdentity\Model\AuthIdentity;

class Root extends StrategyAbstract implements AuthIdentityResolver {
    protected $extName = 'Root';
    
    public function loginPre() {
        //$ips = $this->getOption('request_ips');
        
        $form = new \Zend\Form\Form();
        $form->addElement('checkbox', 'rootLogin', array(
            'label' => 'Login as root',
            'description' => 'This gives you root privileges - you are allowed to do everything on anything',
        ));
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
            if(!empty($formData['rootLogin'])) {
                return $this;
            }
        }
    }
    
    public function resolveAuthIdentity($id) {
        return new AuthIdentity($id, 1, 'root');
    }
    
    public function authenticate() {
        return new Result(Result::SUCCESS, 'root');
    }
    
    
}