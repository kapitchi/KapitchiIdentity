<?php

namespace KapitchiIdentity\Service;

use     Zend\Acl\Role\GenericRole,
        ZfcBase\Service\ModelServiceAbstract,
        KapitchiIdentity\Model\AuthIdentity;
        
class IdentityRegistration extends ModelServiceAbstract {
    
    protected function attachDefaultListeners() {
        parent::attachDefaultListeners();
        
        $mapper = $this->getMapper();
        $this->events()->attach('get.load', function($e) use ($mapper){
            $filter = $e->getParam('identityId');
            if(!$filter) {
                return;
            }
            return $mapper->findByIdentityId($filter);
        });
    }
    
    //getters/setters
    
}