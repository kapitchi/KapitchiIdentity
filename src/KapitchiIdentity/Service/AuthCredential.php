<?php

namespace KapitchiIdentity\Service;

use     Zend\Form\Form,
        KapitchiBase\Service\ModelServiceAbstract,
        KapitchiIdentity\Model\Identity as IdentityModel;

class AuthCredential extends ModelServiceAbstract {
    protected function attachDefaultListeners() {
        parent::attachDefaultListeners();
        
        $events = $this->events();
        $mapper = $this->getMapper();
        $events->attach('get.load', function($e) use ($mapper) {
            if($e->getParam('identityId')) {
                return $mapper->findByIdentityId($e->getParam('identityId'));
            }
        });
    }
}