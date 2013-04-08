<?php
namespace KapitchiIdentity\Controller\Api;

use Zend\View\Model\JsonModel;
use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Http\Response;
use Zend\Mvc\MvcEvent;

/**
 *
 * @author Matus Zeman <mz@kapitchi.com>
 */
class AuthController extends \Zend\Mvc\Controller\AbstractActionController
{
    protected $eventIdentifier = 'KapitchiIdentity\Controller\AuthController';
    protected $loginForm;
    protected $authService;
    
    public function __construct($authService)
    {
        $this->setAuthService($authService);
    }
    
    public function onDispatch(MvcEvent $e)
    {
        \Zend\Stdlib\ErrorHandler::start(E_ALL | E_STRICT | E_USER_ERROR);
        return parent::onDispatch($e);
        \Zend\Stdlib\ErrorHandler::stop(true);
    }
    
    public function loginAction()
    {
        $form = $this->getLoginForm();
        
        $params = array(
            'loginForm' => $form,
        );
        
        $responseData = array();
        $data = (array)\Zend\Json\Json::decode($this->getRequest()->getContent(), \Zend\Json\Json::TYPE_ARRAY);
        
        $this->getEventManager()->trigger('login.pre', $this, $params);
        
        $form->setData($data);
        $form->isValid();
        
        $res = $this->getEventManager()->trigger('login.auth', $this, $params, function($ret) {
            return ($ret instanceof AuthAdapter || $ret instanceof Response);
        });
        $adapter = $res->last();
        if($adapter instanceof Response) {
            return $this->createJsonModel($responseData, $adapter);
        }

        //auth event returns AuthAdapter -- we are ready to authenticate!
        if($adapter instanceof AdapterInterface) {
            $authService = $this->getAuthService();

            $result = $authService->authenticate($adapter);
            
            //do we need to redirect again? example: http auth!
            if($result instanceof Response) {
                return $this->createJsonModel($responseData, $result);
            }
            
            $responseData['result'] = array(
                'code' => $result->getCode(),
                'messages' => $result->getMessages(),
            );

            $params['adapter'] = $adapter;
            $params['result'] = $result;
            $res = $this->getEventManager()->trigger('login.auth.post', $this, $params, function($ret) {
                return $ret instanceof Response;
            });
            $response = $res->last();
            if($response instanceof Response) {
                return $this->createJsonModel($responseData, $response);
            }
        }
        
        $this->getEventManager()->trigger('login.post', $this, $params);

        $responseData['formMessages'] = $form->getMessages();
        
        return $this->createJsonModel($responseData);
    }
    
    public function logoutAction()
    {
        $authService = $this->getAuthService();
        
        $ids = $authService->clearIdentity();
        $responseData = array();
        
        $res = $this->getEventManager()->trigger('logout.post', $this, array(
            'identities' => $ids,
        ), function($ret) {
            return $ret instanceof Response;
        });
        $response = $res->last();
        if($response instanceof Response) {
            return $this->createJsonModel($responseData, $response);
        }
        
        return $this->createJsonModel($responseData);
    }
    
    public function containerAction()
    {
        $container = $this->getAuthService()->getContainer();
        return $this->createJsonModel($this->getAuthService()->getContainerHydrator()->extract($container));
    }
    
    public function currentAction()
    {
        $identity = $this->getAuthService()->getIdentity();
        $data = array(
            'authIdentity' => false
        );
        if($identity) {
            $data['authIdentity'] = $this->getAuthService()->getContainerHydrator()->getAuthIdentityHydrator()->extract($identity);
        }
        return $this->createJsonModel($data);
    }
    
    protected function createJsonModel($responseData, $response = null)
    {
        if($response) {
            $responseData['response'] = array(
                'statusCode' => $response->getStatusCode(),
                'body' => $response->getBody(),
            );
            $response->setStatusCode(200);
        }
        
        return new JsonModel($responseData);
    }
    
    public function getLoginForm()
    {
        return $this->loginForm;
    }

    public function setLoginForm($loginForm)
    {
        $this->loginForm = $loginForm;
    }

    /**
     * 
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