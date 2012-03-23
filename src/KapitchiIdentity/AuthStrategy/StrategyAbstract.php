<?php

namespace KapitchiIdentity\AuthStrategy;

use Zend\EventManager\EventCollection,
        Zend\EventManager\ListenerAggregate,
        KapitchiIdentity\Model\AuthIdentity;

abstract class StrategyAbstract implements Strategy, ListenerAggregate {
    protected $listeners = array();
    protected $viewModel;
    protected $controller;
    
    abstract protected function loginPre();
    abstract protected function loginAuth();
    
    public function attach(EventCollection $events)
    {
        $this->listeners[] = $events->attach('login.pre', array($this, 'onLoginPre'));
        $this->listeners[] = $events->attach('login.auth', array($this, 'onLoginAuth'));
    }
    
    public function detach(EventCollection $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
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