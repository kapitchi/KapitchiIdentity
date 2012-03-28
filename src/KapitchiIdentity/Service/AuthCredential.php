<?php

namespace KapitchiIdentity\Service;

use     Zend\Form\Form,
        KapitchiBase\Service\ModelServiceAbstract,
        KapitchiIdentity\Model\Identity as IdentityModel,
        KapitchiIdentity\Module as Module,
        InvalidArgumentException as PasswordNoMatch;

class AuthCredential extends ModelServiceAbstract {
    protected $module;
    protected $passwordHash;
    
    public function __construct(Module $module) {
        $this->module = $module;
    }
    
    protected function attachDefaultListeners() {
        parent::attachDefaultListeners();
        
        $instance = $this;
        $events = $this->events();
        $mapper = $this->getMapper();
        $events->attach('get.load', function($e) use ($mapper) {
            if($e->getParam('identityId')) {
                return $mapper->findByIdentityId($e->getParam('identityId'));
            }
            if($e->getParam('username')) {
                return $mapper->findByUsername($e->getParam('username'));
            }
        });
        
        $events->attach('persist.pre', function($e) use ($mapper, $instance) {
            $model = $e->getParam('model');
            $data = $e->getParam('data');
            if(isset($data['password']) && isset($data['passwordConfirm'])) {
                //we don't want to do the same mistake as form migth do!
                if($data['password'] != $data['passwordConfirm']) {
                    throw new PasswordNoMatch("Passwords provided do not match");
                }
                
                $hash = $instance->getPasswordHash()->generateHash(($data['password']));
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