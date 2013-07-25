<?php
/**
 * Kapitchi Zend Framework 2 Modules (http://kapitchi.com/)
 *
 * @copyright Copyright (c) 2012-2013 Kapitchi Open Source Team (http://kapitchi.com/open-source-team)
 * @license   http://opensource.org/licenses/LGPL-3.0 LGPL 3.0
 */

namespace KapitchiIdentity\Controller;

use Zend\Authentication\Adapter\AdapterInterface,
    Zend\Mvc\Controller\AbstractActionController,
    Zend\Http\Response;

class AuthController extends AbstractActionController {
    protected $authService;
    protected $loginForm;
    protected $loginViewModel;
    
    public function loginAction() {
        $viewModel = $this->getLoginViewModel();
        
        $form = $this->getLoginForm();
        $form->setAttribute('action', $this->plugin('url')->fromRoute('identity/auth/login'));
        
        //@todo mz: do we do this right?
        $loginText = $this->getTranslator()->translate('Login');
        $form->add(array(
            'name' => 'submit',
            'type' => 'Zend\Form\Element\Submit',
            'options' => array(
                'label' => $loginText,
            ),
            'attributes' => array(
                'value' => $loginText,
            )
        ));

        $params = array(
            'loginForm' => $form,
        );
        
        $this->getEventManager()->trigger('login.pre', $this, $params);
        
        if($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost()->toArray();
            $form->setData($data);
            if($form->isValid()) {
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
            }
        }
        
        $viewModel->form = $form;
        
        $params['viewModel'] = $viewModel;
        $params['form'] = $form;
        
        $this->getEventManager()->trigger('login.post', $this, $params);
        
        return $viewModel;
    }
    
    public function logoutAction() {
        $authService = $this->getAuthService();
        
        $ids = $authService->clearIdentity();
        
        $res = $this->getEventManager()->trigger('logout.post', $this, array(
            'identities' => $ids,
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
            return $instance->redirect()->toRoute('identity/auth/login');
        });
        
        $events->attach('login.auth.post', function($e) use ($instance) {
            if($e->getParam('result')->isValid()) {
                return $instance->redirect()->toRoute('home');
            }
        });
        
        $events->attach('login.post', function($e) {
            $form = $e->getParam('form');
            $element = $form->get('method');
            if(!$element->getValue()) {
                $options = $element->getValueOptions();
                $first = current($options);
                if($first) {
                    $element->setValue($first['value']);
                }
            }
        });
    }
    
    /**
     * @todo
     */
    public function getTranslator()
    {
        return $this->getServiceLocator()->get('Translator');
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
            $this->loginViewModel = new \KapitchiIdentity\View\Model\AuthLogin();
        }
        return $this->loginViewModel;
    }

    public function setLoginViewModel($loginViewModel)
    {
        $this->loginViewModel = $loginViewModel;
    }

}