<?php

namespace KapitchiIdentity\Plugin\AuthStrategy;

use Zend\Authentication\Result,
    ZendService\OAuth2\OAuth2 as OAuth2Service,
    ZendService\OAuth2\Options\Vendor\GoogleOptions,
    KapitchiIdentity\Service\AuthIdentityResolver,
    KapitchiIdentity\Model\AuthIdentity;

class OAuth2 extends StrategyAbstract implements AuthIdentityResolver {
    protected $OAuth2LoginForm;
    protected $extName = 'OAuth2';
    
    public function loginPre() {
        $loader = new \Zend\Loader\StandardAutoloader();
        $loader->registerNamespace('ZendService\OAuth2', 'vendor/ZendService-OAuth2/src/ZendService/OAuth2')->register();
        
        $form = $this->getLoginForm();
        $xtForm = $this->getOAuth2LoginForm();
        $form->addExtSubForm($xtForm, $this->extName);
    }
    
    protected function loginAuth() {
        
        throw new \Exception('N/I');
        
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
                
                $auth = $this->getOAuth2Service($type, $uri);
                $auth->setScope('https://www.googleapis.com/auth/userinfo.profile');
                $token = $auth->getToken(true);
                var_dump($token);
                exit;
                exit;
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
    
    protected function getOAuth2Service($type, $uri = null) {
        switch($type) {
            case 'google':
                $options = new GoogleOptions();
                break;
            default:
                throw new \Exception('XXXXX');
        }
        
        $auth = new OAuth2Service($this->getOption('clientId'), $this->getOption('clientSecret'), $this->getRequest(), $this->getResponse(), $options);
        return $auth;
    }
    
    public function getOAuth2LoginForm() {
        return $this->OAuth2LoginForm;
    }

    public function setOAuth2LoginForm($OAuth2LoginForm) {
        $this->OAuth2LoginForm = $OAuth2LoginForm;
    }


}