<?php

namespace KapitchiIdentity\Controller;

use Zend\Authentication\Adapter as AuthAdapter,
        Exception as AuthException,
        Zend\Stdlib\ResponseDescription as Response,
        Zend\View\Model\ViewModel as ViewModel,
        KapitchiIdentity\Module as Module;

class AuthController extends \Zend\Mvc\Controller\ActionController {
    protected $module;
    protected $loginForm;
    protected $loginViewModel;
    protected $authService;
    
    public function __construct(Module $module) {
        $this->module = $module;
    }
    
    public function registerAction() {
        //TODO
    }
    
    public function logoutAction() {
        $authService = $this->getAuthService();
        $identity = $authService->getIdentity();
        
        $authService->clearIdentity();
        
        $res = $this->events()->trigger('logout.post', $this, array(
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
        $response = $this->getResponse();
        $request = $this->getRequest();
        
        $form = $this->getLoginForm();
        $viewModel = $this->getLoginViewModel();
        $viewModel->setVariable('loginForm', $form);
        
        $params = array(
            'viewModel' => $viewModel,
        );
        
        $this->events()->trigger('login.pre', $this, $params);
        
        $res = $this->events()->trigger('login.auth', $this, $params, function($ret) {
            return ($ret instanceof AuthAdapter || $ret instanceof Response);
        });
        $adapter = $res->last();
        if($adapter instanceof Response) {
            return $adapter;
        }
        
        //init event returns AuthAdapter -- we are ready to authenticate!
        if($adapter instanceof AuthAdapter) {
            //TODO use DI here!
            $authService = $this->getAuthService();

            $result = $authService->authenticate($adapter);
            
            //do we need to redirect again? example: http auth!
            if($result instanceof Response) {
                return $result;
            }
            
            $params['adapter'] = $adapter;
            $params['result'] = $result;
            $res = $this->events()->trigger('authenticate.post', $this, $params, function($ret) {
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
        $events = $this->events();
        $events->attach('logout.post', array($this, 'logoutPost'));
        $events->attach('authenticate.post', array($this, 'loginPost'));
    }
    
    public function logoutPost($e) {
        return $this->redirect()->toRoute('KapitchiIdentity/Auth/Login');
    }
    
    public function loginPost($e) {
        if($e->getParam('result')->isValid()) {
            return $this->redirect()->toRoute('KapitchiIdentity/Identity/Me');
        }
    }
    
    
    //getters/setters
    public function getModule() {
        return $this->module;
    }
    
    public function getLoginForm() {
        return $this->loginForm;
    }

    public function setLoginForm($loginForm) {
        $this->loginForm = $loginForm;
    }

    public function getLoginViewModel() {
        return $this->loginViewModel;
    }

    public function setLoginViewModel($loginViewModel) {
        $this->loginViewModel = $loginViewModel;
    }

    public function getAuthService() {
        return $this->authService;
    }

    public function setAuthService($authService) {
        $this->authService = $authService;
    }


}