<?php

namespace KapitchiIdentity\Controller;

use Zend\Authentication\Adapter\AdapterInterface,
    Zend\Mvc\Controller\AbstractActionController,
    Zend\Http\Response;

class AuthController extends AbstractActionController {
    protected $authService;
    protected $loginForm;
    protected $loginViewModel;
    
    public function loginAction() {
        $form = $this->getLoginForm();
        
        $params = array(
            'loginForm' => $form,
        );
        
        $this->getEventManager()->trigger('login.pre', $this, $params);
        
        $form->setData($this->getRequest()->getPost()->toArray());
        $form->isValid();
        
        $res = $this->getEventManager()->trigger('login.auth', $this, $params, function($ret) {
            return ($ret instanceof AuthAdapter || $ret instanceof Response);
        });
        $adapter = $res->last();
        if($adapter instanceof Response) {
            return $adapter;
        }

        //auth event returns AuthAdapter -- we are ready to authenticate!
        if($adapter instanceof AdapterInterface) {
            $authService = $this->getAuthService();

            $result = $authService->authenticate($adapter);

            //do we need to redirect again? example: http auth!
            if($result instanceof Response) {
                return $result;
            }

            $params['adapter'] = $adapter;
            $params['result'] = $result;
            $res = $this->getEventManager()->trigger('login.auth.post', $this, $params, function($ret) {
                return $ret instanceof Response;
            });
            $result = $res->last();
            if($result instanceof Response) {
                return $result;
            }
        }
        
        $viewModel = $this->getLoginViewModel();
        $viewModel->loginForm = $form;
        
        $params['viewModel'] = $viewModel;
        
        $this->getEventManager()->trigger('login.post', $this, $params);
        
        return $viewModel;
    }
    
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
    
    //listeners
    protected function attachDefaultListeners()
    {
        parent::attachDefaultListeners();
        $events = $this->getEventManager();
        
        $instance = $this;
        $events->attach('logout.post', function($e) use ($instance) {
            return $instance->redirect()->toRoute('kapitchi-identity/auth/login');
        });
        
        $events->attach('login.auth.post', function($e) use ($instance) {
            if($e->getParam('result')->isValid()) {
                return $instance->redirect()->toRoute('kapitchi-identity/profile/me');
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
    
    public function getLoginViewModel()
    {
        if($this->loginViewModel === null) {
            $this->loginViewModel = new \Zend\View\Model\ViewModel();
        }
        return $this->loginViewModel;
    }

    public function setLoginViewModel($loginViewModel)
    {
        $this->loginViewModel = $loginViewModel;
    }

}