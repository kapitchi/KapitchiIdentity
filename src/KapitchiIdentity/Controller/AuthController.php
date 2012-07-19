<?php

namespace KapitchiIdentity\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class AuthController extends AbstractActionController {
    protected $authService;
    protected $loginForm;
    
    public function logoutAction() {
        $authService = $this->getAuthService();
        $identity = $authService->getIdentity();
        
        $authService->clearIdentity();
        
        $res = $this->getEventManager()->trigger('logout.post', $this, array(
            'authIdentity' => $identity,
        ), function($ret) {
            return $ret instanceof Response;
        });
        $response = $res->last();
        if($response instanceof Response) {
            return $response;
        }
    }
    
    public function loginAction() {
        $form = $this->getLoginForm();
        $viewModel = $this->getLoginViewModel();
        $viewModel->setVariable('loginForm', $form);
        
        $params = array(
            'viewModel' => $viewModel,
        );
        
        $this->getEventManager()->trigger('login.pre', $this, $params);
        
        $res = $this->getEventManager()->trigger('login.auth', $this, $params, function($ret) {
            return ($ret instanceof AuthAdapter || $ret instanceof Response);
        });
        $adapter = $res->last();
        if($adapter instanceof Response) {
            return $adapter;
        }
        
        //init event returns AuthAdapter -- we are ready to authenticate!
        if($adapter instanceof AuthAdapter) {
            $authService = $this->getAuthService();

            $result = $authService->authenticate($adapter);
            
            //do we need to redirect again? example: http auth!
            if($result instanceof Response) {
                return $result;
            }
            
            $params['adapter'] = $adapter;
            $params['result'] = $result;
            $res = $this->getEventManager()->trigger('authenticate.post', $this, $params, function($ret) {
                return $ret instanceof Response;
            });
            
            $result = $res->last();
            if($result instanceof Response) {
                return $result;
            }
        }

        return $viewModel;
    }
    
    //listeners
    protected function attachDefaultListeners()
    {
        parent::attachDefaultListeners();
        $events = $this->getEventManager();
        $events->attach('logout.post', function($e) {
            return $this->redirect()->toRoute('kapitchi-identity/auth/login');
        });
        
        $events->attach('authenticate.post', function($e) {
            if($e->getParam('result')->isValid()) {
                return $this->redirect()->toRoute('kapitchi-identity/profile/me');
            }
        });
    }
    
    public function getAuthService() {
        return $this->authService;
    }

    public function setAuthService($authService) {
        $this->authService = $authService;
    }

    public function getLoginForm()
    {
        return $this->loginForm;
    }

    public function setLoginForm($loginForm)
    {
        $this->loginForm = $loginForm;
    }


}