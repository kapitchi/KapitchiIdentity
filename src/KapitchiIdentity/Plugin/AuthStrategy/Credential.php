<?php

namespace KapitchiIdentity\Plugin\AuthStrategy;

use Zend\Authentication\Result,
    KapitchiIdentity\Service\AuthIdentityResolver,
    KapitchiIdentity\Model\AuthIdentity;

class Credential extends StrategyAbstract implements AuthIdentityResolver {
    protected $credentialMapper;
    protected $passwordHash;
    protected $credentialLoginForm;
    protected $extName = 'Credential';
    
    public function loginPre() {
        $request = $this->getRequest();
        $form = $this->getLoginForm();
        
        $credentialForm = $this->getCredentialLoginForm();
        $form->addExtSubForm($credentialForm, $this->extName);
    }
    
    protected function loginAuth() {
        $request = $this->getRequest();
        if($request->isPost()) {
            $postData = $request->post()->toArray();
            if(!isset($postData['exts'][$this->extName])) {
                return;
            }
            
            $formData = $postData['exts'][$this->extName];
            $form = $this->getCredentialLoginForm();
            if($form->isValid($formData)) {
                $values = $form->getValues();
                $val = $values[$this->extName];
                $this->username = $val['username'];
                $this->password = $val['password'];
                
                return $this;
            }
        }
    }
    
    public function resolveAuthIdentity($id) {
        $mapper = $this->getCredentialMapper();
        $user = $mapper->findByUsername($id);
        
        return new AuthIdentity($id, $user->getIdentityId());
    }
    
    public function authenticate() {
        $mapper = $this->getCredentialMapper();
        $user = $mapper->findByUsername($this->username);
        $form = $this->getCredentialLoginForm();
        if(!$user) {
            $form->getElement('username')->addError('User not found');
            return new Result(Result::FAILURE_IDENTITY_NOT_FOUND, null);
        }
        
        $hash = $user->getPasswordHash();
        if(!$this->getPasswordHash()->isEqual($this->password, $hash)) {
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
    

    public function getPasswordHash() {
        return $this->passwordHash;
    }

    public function setPasswordHash($passwordHash) {
        $this->passwordHash = $passwordHash;
    }

    public function getCredentialMapper() {
        return $this->credentialMapper;
    }

    public function setCredentialMapper($credentialMapper) {
        $this->credentialMapper = $credentialMapper;
    }
    
}