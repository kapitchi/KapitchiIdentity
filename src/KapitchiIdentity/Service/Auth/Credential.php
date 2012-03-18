<?php

namespace KapitchiIdentity\Service\Auth;

use Zend\EventManager\EventCollection,
        Zend\Authentication\Result,
        Zend\Form\Form,
        KapitchiIdentity\Model\AuthIdentity,
        KapitchiIdentity\Service\Acl as AclService;

class Credential extends StrategyAbstract implements AuthIdentityResolver {
    protected $credentialMapper;
    protected $credentialLoginForm;
    
    public function init() {
        $request = $this->getRequest();
        
        $form = $this->getLoginForm();
        
        $credentialForm = $this->getCredentialLoginForm();
        $form->addExtSubForm($credentialForm, 'KapitchiIdentity_Credential');
        
        if($request->isPost()) {
            //TODO this should be partial check only!!!
            $postData = $request->post()->toArray();
            if($form->isValid($postData)) {
                //return this strategy which implements auth adapter also
                $values = $form->getExtSubForm('KapitchiIdentity_Credential')->getValues();
                $val = $values['KapitchiIdentity_Credential'];
                $this->username = $val['username'];
                $this->password = $val['password'];
                
                return $this;
            }
        }
    }
    
    public function resolveAuthIdentity($id) {
        $mapper = $this->getCredentialMapper();
        $user = $mapper->findByUsername($id);
        
        return new AuthIdentity($id, AclService::ROLE_USER, $user->getIdentityId());
    }
    
    public function authenticate() {
        $mapper = $this->getCredentialMapper();
        $user = $mapper->findByUsername($this->username);
        $form = $this->getCredentialLoginForm();
        if(!$user) {
            $form->getElement('username')->addError('User not found');
            return new Result(Result::FAILURE_IDENTITY_NOT_FOUND, null);
        }
        
        if($user->getPassword() != $this->password) {
            $form->getElement('password')->addError('Incorrect password');
            return new Result(Result::FAILURE_CREDENTIAL_INVALID, null);
        }
        
        return new Result(Result::SUCCESS, $this->username);
    }
    
    public function getCredentialLoginForm() {
        return $this->credentialLoginForm;
    }

    public function setCredentialLoginForm($credentialLoginForm) {
        $this->credentialLoginForm = $credentialLoginForm;
    }

    public function getCredentialMapper() {
        return $this->credentialMapper;
    }

    public function setCredentialMapper($credentialMapper) {
        $this->credentialMapper = $credentialMapper;
    }
    
}