<?php

namespace KapitchiIdentity\Controller;

use Zend\Authentication\Adapter\AdapterInterface,
    Zend\Mvc\Controller\AbstractActionController,
    Zend\Http\Response;

class AuthSessionController extends AbstractActionController {
    protected $sessionProvider;
    protected $authService;
    
    public function indexAction()
    {
        $ids = $this->getAuthService()->getContainer()->getIdentities();
        
        return array(
            'authIdentities' => $ids
        );
    }
    
    public function switchAction()
    {
        $sessionId = $this->getEvent()->getRouteMatch()->getParam('sessionId');
        $this->getSessionProvider()->setCurrentSessionId($sessionId);
        
        return $this->plugin('redirect')->toRoute('identity/auth-session/default', array('action' => 'index'));
    }

    /**
     * 
     * @return \KapitchiIdentity\Service\AuthSessionProvider\Session
     */
    public function getSessionProvider()
    {
        return $this->sessionProvider;
    }

    public function setSessionProvider($sessionProvider)
    {
        $this->sessionProvider = $sessionProvider;
    }
    
    /**
     * @return \KapitchiIdentity\Service\Auth
     */
    public function getAuthService()
    {
        return $this->authService;
    }

    public function setAuthService($authService)
    {
        $this->authService = $authService;
    }
    
}