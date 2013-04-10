<?php
/**
 * Kapitchi Zend Framework 2 Modules (http://kapitchi.com/)
 *
 * @copyright Copyright (c) 2012-2013 Kapitchi Open Source Team (http://kapitchi.com/open-source-team)
 * @license   http://opensource.org/licenses/LGPL-3.0 LGPL 3.0
 */

namespace KapitchiIdentity\View\Helper;

use Zend\View\Helper\AbstractHelper;

class AuthIdentity extends AbstractHelper
{
    protected $authService;
    protected $identityService;
    
//    public function __invoke()
//    {
//        throw new \Exception('N/I');
//    }
    
    public function getDisplayName() {
        $authService = $this->getAuthService();
        if($authService->hasIdentity()) {
            $localId = $authService->getLocalIdentityId();
            $entity = $this->getIdentityById($localId);
            return $entity->getDisplayName();
        }
        
        return '';
    }
    
    public function getLocalId()
    {
        $authService = $this->getAuthService();
        return $authService->getLocalIdentityId();
    }
    
    public function hasIdentity() {
        $authService = $this->getAuthService();
        return $authService->hasIdentity();
    }
    
    protected function getIdentityById($id) {
        $identity = $this->getIdentityService()->find($id);
        if(!$identity) {
            throw new \Exception("No identity [$id]");
        }
        return $identity;
    }
    
    public function getAuthService()
    {
        return $this->authService;
    }

    public function setAuthService($authService)
    {
        $this->authService = $authService;
    }

    public function getIdentityService()
    {
        return $this->identityService;
    }

    public function setIdentityService($identityService)
    {
        $this->identityService = $identityService;
    }

}