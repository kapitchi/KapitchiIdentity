<?php

namespace KapitchiIdentity\Service\Auth;

use Zend\EventManager\EventCollection;

class Http extends StrategyAbstract {
    private $adapter;
    
    public function init() {
        return $this->getAdapter();
    }
    
    public function authenticate() {
        $adapter = $this->getAdapter();
        
        $result = $adapter->authenticate();
        if(!$result->isValid()) {
            return $adapter->getResponse();
        }
    }
    
    public function getAdapter() {
        if($this->adapter === null) {
            //return $e->getTarget()->redirect()->toRoute('kapitchiidentity');
            $cont = $this->getController();
            $adapter = $cont->getLocator()->get('Zend\Authentication\Adapter\Http');

            $res = new \Zend\Authentication\Adapter\Http\FileResolver('./passwords.txt');

            $adapter->setBasicResolver($res);
            $adapter->setRequest($this->getRequest());
            $adapter->setResponse($this->getResponse());
            
            $this->adapter = $adapter;
        }
        
        return $this->adapter;
    }
    
}