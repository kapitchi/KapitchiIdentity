<?php

namespace KapitchiIdentity\Service;

use KapitchiEntity\Service\EntityService;

class AuthCredential extends EntityService
{
    //protected $passwordHash;
    
//    protected function attachDefaultListeners() {
//        parent::attachDefaultListeners();
//        
//        $instance = $this;
//        $events = $this->events();
//        $mapper = $this->getMapper();
//        
//        //get
//        $events->attach('get.load', function($e) use ($mapper) {
//            if($e->getParam('identityId')) {
//                return $mapper->findByIdentityId($e->getParam('identityId'));
//            }
//            if($e->getParam('username')) {
//                return $mapper->findByUsername($e->getParam('username'));
//            }
//        });
//        
//        
//        //persist
//        $events->attach('persist.pre', function($e) use ($mapper, $instance) {
//            $model = $e->getParam('model');
//            $data = $e->getParam('data');
//            if(isset($data['password']) && isset($data['passwordConfirm'])) {
//                //we don't want to do the same mistake as form migth do!
//                if($data['password'] != $data['passwordConfirm']) {
//                    throw new PasswordNoMatch("Passwords provided do not match");
//                }
//                
//                $hash = $instance->getPasswordHash()->encrypt($data['password']);
//                $model->setPasswordHash($hash);
//            }
//        });
//    }
    
//    public function getPasswordHash() {
//        return $this->passwordHash;
//    }
//
//    public function setPasswordHash($passwordHash) {
//        $this->passwordHash = $passwordHash;
//    }

}