<?php

namespace KapitchiIdentity\Service\Auth;

use Zend\EventManager\EventCollection,
        Zend\Authentication\Result,
        Zend\Form\Form,
        KapitchiIdentity\Model\AuthIdentity;

class Test extends StrategyAbstract implements AuthIdentityResolver {
    
    public function init($e) {
        $request = $e->getParam('request');
        
        $form = $e->getParam('viewModel')->form;
        
        $credentialForm = new Form();
        $credentialForm->addElement('text', 'username', array(
            'label' => 'Username',
        ));
        $credentialForm->addElement('password', 'password', array(
            'label' => 'Password',
        ));
        
        $form->addSubForm($credentialForm, 'credential');
        
        if($request->isPost()) {
            if($form->isValid($request->post()->toArray())) {
                //return this strategy which implements auth adapter also
                return $this;
            }
        }
    }
    
    public function resolveAuthIdentity($id) {
        $authId = new AuthIdentity($id, 'user', 1);
        return $authId;
    }
    
    public function authenticate() {
        $result = new Result(Result::SUCCESS, 'mytestidentity');
        return $result;
    }
    
}