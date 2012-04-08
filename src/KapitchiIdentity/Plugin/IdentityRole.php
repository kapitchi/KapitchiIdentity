<?php

namespace KapitchiIdentity\Plugin;

use ZfcBase\Model\ModelAbstract;

class IdentityRole extends \KapitchiBase\Plugin\ModelPlugin {
    protected $modelServiceClass = 'KapitchiIdentity\Service\Identity';
    protected $modelFormClass = 'KapitchiIdentity\Form\Identity';
    protected $extName = 'IdentityRole';
    
    public function getModel(ModelAbstract $model) {
        $service = $this->getLocator()->get('KapitchiIdentity\Service\IdentityRole');
        $model = $service->get(array(
            'identityId' => $model->getId()
        ));
        
        return $model;
    }
    
    public function getForm() {
        $form = $this->getLocator()->get('KapitchiIdentity\Form\IdentityRole');
        return $form;
    }
    
    public function persistModel(ModelAbstract $model, array $data, $extData) {
        if($extData) {
            $extData['identityId'] = $model->getId();
            return $this->getLocator()->get('KapitchiIdentity\Service\IdentityRole')->persist($extData);
        }
    }
    
    public function removeModel(ModelAbstract $model) {
        var_dump($model);
        exit;
    }
    
}