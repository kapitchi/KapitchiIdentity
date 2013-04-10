<?php
/**
 * Kapitchi Zend Framework 2 Modules (http://kapitchi.com/)
 *
 * @copyright Copyright (c) 2012-2013 Kapitchi Open Source Team (http://kapitchi.com/open-source-team)
 * @license   http://opensource.org/licenses/LGPL-3.0 LGPL 3.0
 */

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