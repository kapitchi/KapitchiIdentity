<?php

namespace KapitchiIdentity\Plugin\AuthStrategy;

use Zend\Mvc\ApplicationInterface,
    KapitchiBase\Module\Plugin\PluginAbstract,
    KapitchiIdentity\Model\AuthIdentity;

abstract class StrategyAbstract extends PluginAbstract implements StrategyInterface {
    protected $viewModel;
    protected $controller;
    
    abstract protected function loginPre();
    abstract protected function loginAuth();
    
    public function bootstrap(ApplicationInterface $app) {
        if($this->isIpAllowed($_SERVER['REMOTE_ADDR'])) {
            $events = $app->events()->getSharedManager();
            $events->attach('KapitchiIdentity\Controller\AuthController', 'login.pre', array($this, 'onLoginPre'));
            $events->attach('KapitchiIdentity\Controller\AuthController', 'login.auth', array($this, 'onLoginAuth'));
        }
    }
    
    protected function isIpAllowed($ip) {
        $remIps = $this->getOption('remote_ips', false);
        if($remIps) {
            if(!is_array($remIps)) {
                $remIps = array(
                    'reqIps' => true
                );
            }
            
            $resolvedIpAllow = false;
            foreach($remIps as $remIp => $allowed) {
                if(strpos($ip, $remIp) === 0) {
                    $resolvedIpAllow = $allowed;
                }
            }
            
            return $resolvedIpAllow;
        }
        
        //no remote_ips? default to allowed!
        return true;
    }
    
    public function onLoginPre($e) {
        $this->setViewModel($e->getParam('viewModel'));
        $this->setController($e->getTarget());
        
        return $this->loginPre();
    }
    
    public function onLoginAuth($e) {
        $this->setViewModel($e->getParam('viewModel'));
        $this->setController($e->getTarget());
        
        return $this->loginAuth();
    }
    
    public function getController() {
        return $this->controller;
    }

    public function setController($controller) {
        $this->controller = $controller;
    }

    public function getViewModel() {
        return $this->viewModel;
    }

    public function setViewModel($viewModel) {
        $this->viewModel = $viewModel;
    }

    public function getLoginForm() {
        return $this->getViewModel()->loginForm;
    }

    public function getResponse() {
        return $this->getController()->getResponse();
    }

    public function getRequest() {
        return $this->getController()->getRequest();
    }

}