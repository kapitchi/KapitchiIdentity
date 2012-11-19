<?php

namespace KapitchiIdentity\Service;

use KapitchiEntity\Service\EntityService;

class AuthCredential extends EntityService
{
    protected $passwordGenerator;
    
    protected function attachDefaultListeners() {
        parent::attachDefaultListeners();
        
        $instance = $this;
        $events = $this->getEventManager();
        $mapper = $this->getMapper();
        
        //persist
        $events->attach('persist', function($e) use ($mapper, $instance) {
            $entity = $e->getParam('entity');
            $data = $e->getParam('data');
            if(!empty($data['password']) && !empty($data['passwordConfirm'])) {
                //mz: we don't want to do the same mistake as form migth do!
                if($data['password'] != $data['passwordConfirm']) {
                    throw new \Exception("Passwords provided do not match");
                }
                
                $hash = $instance->getPasswordGenerator()->create($data['password']);
                $mapper->updatePasswordHash($entity->getId(), $hash);
            }
        });
    }
    
    public function getPasswordGenerator()
    {
        return $this->passwordGenerator;
    }

    public function setPasswordGenerator($passwordGenerator)
    {
        $this->passwordGenerator = $passwordGenerator;
    }

}