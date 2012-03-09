<?php

namespace KapitchiIdentity\Service\Auth;

use Zend\EventManager\EventCollection,
        Zend\Authentication\Result,
        Zend\Form\Form,
        KapitchiIdentity\Model\AuthIdentity,
        KapitchiIdentity\Service\Acl as AclService;

class Credential extends StrategyAbstract implements AuthIdentityResolver {
    protected $credentialMapper;
    protected $credentialForm;
    
    public function init() {
        $request = $this->getRequest();
        
        $form = $this->getForm();
        
        $this->credentialForm = new Form();
        $this->credentialForm->addElement('text', 'username', array(
            'label' => 'Username',
        ));
        $this->credentialForm->addElement('password', 'password', array(
            'label' => 'Password',
        ));
        
        $form->addSubForm($this->credentialForm, 'credential');
        
        if($request->isPost()) {
            //TODO this should be partial check only!!!
            if($form->isValid($request->post()->toArray())) {
                //return this strategy which implements auth adapter also
                $values = $form->getValues();
                $val = $values['credential'];
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
        $form = $this->credentialForm;
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
    
    public function getCredentialMapper() {
        return $this->credentialMapper;
    }

    public function setCredentialMapper($credentialMapper) {
        $this->credentialMapper = $credentialMapper;
    }
    
}