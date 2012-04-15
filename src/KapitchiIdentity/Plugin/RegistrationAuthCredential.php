<?php

namespace KapitchiIdentity\Plugin;

use Zend\EventManager\StaticEventManager,
    Zend\Mvc\AppContext as Application,
    ZfcBase\Model\ModelAbstract,
    KapitchiBase\Module\Plugin\ModelPlugin;

class RegistrationAuthCredential extends ModelPlugin {
    protected $modelServiceClass = 'KapitchiIdentity\Service\Registration';
    protected $modelFormClass = 'KapitchiIdentity\Form\Registration';
    protected $extName = 'AuthCredential';
    
    public function getForm() {
        $form = $this->getLocator()->get('KapitchiIdentity\Form\AuthCredential\Registration');
        return $form;
    }

    public function getModel(ModelAbstract $model) {
        var_dump($model);
        exit;
    }

    public function persistModel(ModelAbstract $model, array $data, $extData) {
        //this assumes RegistrationIdentity plugin has been called already.
        $identity = $model->ext('Identity');
        $extData['identityId'] = $identity->getId();
        $ret = $this->getLocator()->get('KapitchiIdentity\Service\AuthCredential')->persist($extData);
        return $ret['model'];
    }

    public function removeModel(ModelAbstract $model) {
        var_dump($model);
        exit;
    }
}