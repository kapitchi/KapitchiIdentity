<?php

namespace KapitchiIdentity\Service;

use     Zend\Form\Form,
        ZfcBase\Service\ModelServiceAbstract,
        KapitchiIdentity\Model\Identity as IdentityModel;

class Identity extends ModelServiceAbstract {
    protected function attachDefaultListeners() {
        parent::attachDefaultListeners();
        
        $events = $this->events();
        $events->attach('persist.pre', function($e) {
            $model = $e->getParam('model');
            if($model->getId()) {
                //update
                
            }
            else {
                //insert 
                $model->setCreated(new \DateTime());
            }
        });
    }
}