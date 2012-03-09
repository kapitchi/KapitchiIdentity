<?php

namespace KapitchiIdentity\View\Model;

use Zend\View\Model\ViewModel;

class AuthLogin extends ViewModel {
    private $authService;

    public function getAuthOptions() {
        return array('credential', 'openid', 'facebook-connect');
    }
    
    public function setAuthService($authService) {
        $this->authService = $authService;
    }
    
    public function getAuthService() {
        return $this->authService;
    }
}