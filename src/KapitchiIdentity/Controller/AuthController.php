<?php

namespace KapitchiIdentity\Controller;

use Zend\Authentication\Adapter as AuthAdapter,
        Exception as AuthException,
        Zend\Stdlib\ResponseDescription as Response,
        Zend\View\Model\ViewModel as ViewModel;

class AuthController extends \Zend\Mvc\Controller\ActionController {
    public function indexAction() {
        $authService = $this->getLocator()->get('KapitchiIdentity\Service\Auth');

        $aclService = $this->getLocator()->get('KapitchiIdentity\Service\Acl');
        $ret = $aclService->isAllowed('kapitchiidentity.auth.indexAction');
        //$new = new \Zend\Session\SessionManager();
        //$new->start();
        var_dump($ret);
        var_dump($_SESSION);
        exit;
        /*$ret = $this->getLocator()->get('KapitchiIdentity\Service\Identity');
        $x = $ret->persist(array(
            'id' => 3,
            'created' => '2012-12-12 10:00:00',
            'ownerId' => 1,
        ));
        */

    }
    
    public function logoutAction() {
        $authService = $this->getLocator()->get('KapitchiIdentity\Service\Auth');
        $authService->clearIdentity();
        
        $res = $this->events()->trigger('logout.post', $this, array(), function($ret) {
            return $ret instanceof Response;
        });
        $response = $res->last();
        if($response instanceof Response) {
            return $response;
        }
    }
    
    public function loginAction() {
        //test
        //$this->events()->attach($this->getLocator()->get('KapitchiIdentity\Service\Auth\Http'));
        //$this->events()->attach($this->getLocator()->get('KapitchiIdentity\Service\Auth\Test'));
        $this->events()->attach($this->getLocator()->get('KapitchiIdentity\Service\Auth\Credential'));
        //END
        
        $response = $this->getResponse();
        $request = $this->getRequest();
        
        $form = $this->getLocator()->get('KapitchiIdentity\Form\Login');
        $viewModel = $this->getLocator()->get('KapitchiIdentity\View\Model\AuthLogin');
        $viewModel->setVariable('form', $form);
        
        $params = array(
            'request' => $request,
            'response' => $response,
            'viewModel' => $viewModel,
        );
        
        $res = $this->events()->trigger('authenticate.init', $this, $params, function($ret) {
            return ($ret instanceof AuthAdapter || $ret instanceof Response);
        });
        $adapter = $res->last();
        if($adapter instanceof Response) {
            return $adapter;
        }
        
        //init event returns AuthAdapter -- we are ready to authenticate!
        if($adapter instanceof AuthAdapter) {
            $authService = $this->getLocator()->get('KapitchiIdentity\Service\Auth');

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
            
            $response = $res->last();
            if($response instanceof Response) {
                return $response;
            }
        }

        return $viewModel;
    }
    
    protected function attachDefaultListeners()
    {
        parent::attachDefaultListeners();
        $events = $this->events();
        $events->attach('logout.post', array($this, 'logoutPost'));
        $events->attach('authenticate.post', array($this, 'loginPost'));
    }
    
    public function logoutPost($e) {
        return $this->redirect()->toRoute('kapitchiidentity');
    }
    
    public function loginPost($e) {
        if($e->getParam('result')->isValid()) {
            return $this->redirect()->toRoute('kapitchiidentity');
        }
    }
}