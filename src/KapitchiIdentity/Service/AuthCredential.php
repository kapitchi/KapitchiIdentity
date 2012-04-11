<?php

namespace KapitchiIdentity\Service;

use     Zend\Form\Form,
        ZfcBase\Service\ModelServiceAbstract,
        KapitchiIdentity\Model\Identity as IdentityModel,
        KapitchiIdentity\Module as Module,
        InvalidArgumentException as PasswordNoMatch;

class AuthCredential extends ModelServiceAbstract {
    protected $module;
    protected $passwordHash;
    
    public function getModelPrototype() {
        $model = clone $this->modelPrototype;
        //there is problem with Zend\Di passwordHash set on service set it to model also!
        $model->setPasswordHash(null);
        return $model;
    }

    public function __construct(Module $module) {
        $this->module = $module;
    }
    
    protected function attachDefaultListeners() {
        parent::attachDefaultListeners();
        
        $instance = $this;
        $events = $this->events();
        $mapper = $this->getMapper();
        
        //get
        $events->attach('get.load', function($e) use ($mapper) {
            if($e->getParam('identityId')) {
                return $mapper->findByIdentityId($e->getParam('identityId'));
            }
            if($e->getParam('username')) {
                return $mapper->findByUsername($e->getParam('username'));
            }
        });
        
        
        //persist
        $events->attach('persist.pre', function($e) use ($mapper, $instance) {
            $model = $e->getParam('model');
            $data = $e->getParam('data');
            if(isset($data['password']) && isset($data['passwordConfirm'])) {
                //we don't want to do the same mistake as form migth do!
                if($data['password'] != $data['passwordConfirm']) {
                    throw new PasswordNoMatch("Passwords provided do not match");
                }
                
                $hash = $instance->getPasswordHash()->encrypt($data['password']);
                $model->setPasswordHash($hash);
            }
        });
    }
    
    //getters/setters
    public function getModule() {
        return $this->module;
    }
    
    public function getPasswordHash() {
        return $this->passwordHash;
    }

    public function setPasswordHash($passwordHash) {
        $this->passwordHash = $passwordHash;
    }

}