<?php

namespace KapitchiIdentity\AuthStrategy;

use Zend\EventManager\EventCollection,
        Zend\Authentication\Result,
        Zend\Form\Form,
        KapitchiIdentity\Service\AuthIdentityResolver,
        KapitchiIdentity\Model\AuthIdentity;

class OAuth2 extends StrategyAbstract {
    protected $extName = 'OAuth2';
    protected $OAuth2LoginForm;
    
    public function __construct() {
        $loader = new \Zend\Loader\StandardAutoloader();
        $loader->registerNamespace('ZendService\OAuth2', 'vendor/ZendService-OAuth2/src/ZendService/OAuth2')->register();
    }
    
    public function loginPre() {
        $form = $this->getLoginForm();
        
        $extForm = $this->getOAuth2LoginForm();
        $form->addExtSubForm($extForm, $this->extName);
    }
    
    protected function loginAuth() {
        $request = $this->getRequest();
        if($request->isPost()) {
            $postData = $request->post()->toArray();
            if(!isset($postData['exts'][$this->extName])) {
                return;
            }
            
            $formData = $postData['exts'][$this->extName];
            $form = $this->getOAuth2LoginForm();
            if($form->isValid($formData)) {
                $values = $form->getValues();
                $val = $values[$this->extName];
                $endpoint = $val['endpoint'];
                
                $auth = new \ZendService\OAuth2\OAuth2(
                    'USER',
                    'PASS',
                    $this->getRequest(),
                    'google'
                );
                $auth->setConfigValue('stage1', 'scope', array('https://www.googleapis.com/auth/userinfo.profile', 'scope'));
                $token = $auth->getToken(true);
                
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
    
    public function getOAuth2LoginForm() {
        return $this->OAuth2LoginForm;
    }

    public function setOAuth2LoginForm($OAuth2LoginForm) {
        $this->OAuth2LoginForm = $OAuth2LoginForm;
    }

    
}