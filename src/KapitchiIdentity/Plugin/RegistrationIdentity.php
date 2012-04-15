<?php

namespace KapitchiIdentity\Plugin;

use ZfcBase\Model\ModelAbstract,
    KapitchiBase\Module\Plugin\ModelPlugin;

class RegistrationIdentity extends ModelPlugin {
    protected $modelServiceClass = 'KapitchiIdentity\Service\Registration';
    protected $priority = 100;
    protected $extName = 'Identity';
    
    public function getModel(ModelAbstract $model) {
        $service = $this->getLocator()->get('KapitchiIdentity\Service\IdentityRegistration');
        $ret = $service->get(array(
            'priKey' => $model->getId()
        ));
        
        $service = $this->getLocator()->get('KapitchiIdentity\Service\Identity');
        $ret = $service->get(array(
            'priKey' => $ret->getIdentityId()
        ));
        
        return $ret;
    }
    
    public function persistModel(ModelAbstract $model, array $data, $extData) {
        $service = $this->getLocator()->get('KapitchiIdentity\Service\Identity');
        $ret = $service->persist(array());
        $identity = $ret['model'];

        $service = $this->getLocator()->get('KapitchiIdentity\Service\IdentityRole');
        $ret = $service->persist(array(
            'identityId' => $identity->getId(),
            'roleId' => $this->getOption('role'),
        ));
        
        //wire registration with identity
        $service = $this->getLocator()->get('KapitchiIdentity\Service\IdentityRegistration');
        $ret = $service->persist(array(
            'identityId' => $identity->getId(),
            'registrationId' => $model->getId(),
        ));
        
        return $identity;
    }
    
    public function removeModel(ModelAbstract $model) {
        var_dump($model);
        exit;
    }
    
}