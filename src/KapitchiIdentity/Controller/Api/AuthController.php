<?php
namespace KapitchiIdentity\Controller\Api;

use Zend\View\Model\JsonModel;
use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Http\Response;

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
    
    public function loginAction() {
        $form = $this->getLoginForm();
        
        $params = array(
            'loginForm' => $form,
        );
        
        $responseData = array();
        $data = \Zend\Json\Json::decode($this->getRequest()->getContent(), \Zend\Json\Json::TYPE_ARRAY);
        
        $this->getEventManager()->trigger('login.pre', $this, $params);
        
        $form->setData($data);
        $form->isValid();
        
        $res = $this->getEventManager()->trigger('login.auth', $this, $params, function($ret) {
            return ($ret instanceof AuthAdapter || $ret instanceof Response);
        });
        $adapter = $res->last();
        if($adapter instanceof Response) {
            //TODO
        }

        //auth event returns AuthAdapter -- we are ready to authenticate!
        if($adapter instanceof AdapterInterface) {
            $authService = $this->getAuthService();

            $result = $authService->authenticate($adapter);
            
            //do we need to redirect again? example: http auth!
            if($result instanceof Response) {
                $responseData['response'] = array(
                    'statusCode' => $result->getStatusCode(),
                    'body' => $result->getBody(),
                );
                $result->setStatusCode(200);
                return new JsonModel($responseData);
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
            $result = $res->last();
            if($result instanceof Response) {
                $responseData['response'] = array(
                    'statusCode' => $result->getStatusCode(),
                    'body' => $result->getBody(),
                );
                $result->setStatusCode(200);
                return new JsonModel($responseData);
            }
        }
        
        $responseData['formMessages'] = $form->getMessages();
        
        $this->getEventManager()->trigger('login.post', $this, $params);
        
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

    public function getAuthService()
    {
        return $this->authService;
    }

    public function setAuthService($authService)
    {
        $this->authService = $authService;
    }
}