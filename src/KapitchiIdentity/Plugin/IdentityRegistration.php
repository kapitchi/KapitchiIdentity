<?php

namespace KapitchiIdentity\Plugin;

use ZfcBase\Model\ModelAbstract,
    KapitchiBase\Module\Plugin\ModelPlugin;

class IdentityRegistration extends ModelPlugin {
    protected $modelServiceClass = 'KapitchiIdentity\Service\Identity';
    protected $modelFormClass = 'KapitchiIdentity\Form\Identity';
    protected $extName = 'Registration';
    
    public function getModel(ModelAbstract $model) {
        $service = $this->getLocator()->get('KapitchiIdentity\Service\Registration');
        
        $model = $service->get(array(
            'identityId' => $model->getId()
        ));
        
        return $model;
    }
    
    public function persistModel(ModelAbstract $model, array $data, $extData) {
    }
    
    public function removeModel(ModelAbstract $model) {       
        $authCredentialModel = $this->getModel($model);      
        return $this->getLocator()->get('KapitchiIdentity\Service\Registration')->remove($authCredentialModel->getId());        
    }
    
}