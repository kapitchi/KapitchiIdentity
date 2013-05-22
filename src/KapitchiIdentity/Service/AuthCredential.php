<?php
/**
 * Kapitchi Zend Framework 2 Modules (http://kapitchi.com/)
 *
 * @copyright Copyright (c) 2012-2013 Kapitchi Open Source Team (http://kapitchi.com/open-source-team)
 * @license   http://opensource.org/licenses/LGPL-3.0 LGPL 3.0
 */

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
                if($data['password'] !== $data['passwordConfirm']) {
                    throw new \Exception("Passwords provided do not match");
                }
                
                $hash = $instance->getPasswordGenerator()->create($data['password']);
                $entity->setPasswordHash($hash);
            }
        }, 10);
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