<?php

namespace KapitchiIdentity\Plugin;

use ZfcBase\Model\ModelAbstract,
    KapitchiBase\Module\Plugin\ModelPlugin;

class IdentityAuthCredential extends ModelPlugin {
    protected $modelServiceClass = 'KapitchiIdentity\Service\Identity';
    protected $modelFormClass = 'KapitchiIdentity\Form\Identity';
    protected $extName = 'AuthCredential';
    
    public function getModel(ModelAbstract $model) {
        $service = $this->getLocator()->get('KapitchiIdentity\Service\AuthCredential');
        $model = $service->get(array(
            'identityId' => $model->getId()
        ));
        
        return $model;
    }
    
    public function getForm() {
        $form = $this->getLocator()->get('KapitchiIdentity\Form\AuthCredential');
        return $form;
    }
    
    public function persistModel(ModelAbstract $model, array $data, $extData) {
        if(!empty($extData)) {
            $extData['identityId'] = $model->getId();
            return $this->getLocator()->get('KapitchiIdentity\Service\AuthCredential')->persist($extData);
        }
        return null;
    }
    
    public function removeModel(ModelAbstract $model) {
        var_dump($model);
        exit;
    }
    
}