<?php

namespace KapitchiIdentity\Plugin;

use ZfcBase\Model\ModelAbstract;

class IdentityRoleModel extends \KapitchiBase\Plugin\ModelPlugin {
    protected $modelServiceClass = 'KapitchiIdentity\Service\Identity';
    protected $modelFormClass = 'KapitchiIdentity\Form\Identity';
    protected $extName = 'ZZZZ';
    
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
    
    public function persistModel(ModelAbstract $model, array $extData, array $data) {
        $extData['identityId'] = $model->getId();
        return $this->getLocator()->get('KapitchiIdentity\Service\AuthCredential')->persist($extData);
    }
    
    public function removeModel(ModelAbstract $model) {
        var_dump($model);
        exit;
    }
    
}