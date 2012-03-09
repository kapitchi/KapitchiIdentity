<?php

namespace KapitchiIdentity\Service\Auth;

use Zend\EventManager\EventCollection,
        Zend\Authentication\Result,
        Zend\Form\Form,
        KapitchiBase\Service\ServiceAbstract,
        KapitchiIdentity\Model\AuthIdentity;

abstract class StrategyAbstract extends ServiceAbstract implements Strategy, \Zend\EventManager\ListenerAggregate {
    protected $listeners = array();
    protected $viewModel;
    protected $controller;
    
    abstract protected function init();
    
    public function attach(EventCollection $events)
    {
        $this->listeners[] = $events->attach('authenticate.init', array($this, 'onInit'));
    }
    
    public function onInit($e) {
        $this->setViewModel($e->getParam('viewModel'));
        $this->setController($e->getTarget());
        
        return $this->init();
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

    public function getForm() {
        return $this->getViewModel()->form;
    }

    public function getResponse() {
        return $this->getController()->getResponse();
    }

    public function getRequest() {
        return $this->getController()->getRequest();
    }


//    public function resolveAuthIdentity($id) {
//        $params = array(
//            'identity' => $id
////        );
////        $result = $this->events()->trigger('resolveAuthIdentity', $this, $params, function($ret) {
////            return $ret instanceof AuthIdentity;
////        });
////        if($result->stopped()) {
////            return $result->last();
////        }
//        
//        $authId = new AuthIdentity($id, 'user', 1);
//        return $authId;
//    }
    
    
    public function detach(EventCollection $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }
}