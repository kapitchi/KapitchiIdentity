<?php

namespace KapitchiIdentity\Model\Mapper;

use ZfcBase\Model\ModelAbstract,
    ZfcBase\Mapper\DbAdapterMapper,
    KapitchiIdentity\Model\Mapper\IdentityRegistrationInterface,
    KapitchiIdentity\Model\IdentityRegistration;

class IdentityRegistrationDbAdapter extends DbAdapterMapper implements IdentityRegistrationInterface {
    protected $tableName = 'identity_registration';
    protected $modelPrototype;
    
    public function findByPriKey($key) {
        throw new \Exception('TODO');
    }
    
    public function persist(ModelAbstract $model) {
        $table = $this->getTableGateway($this->tableName, true);
        $data = $this->toScalarValueArray(array(
            'identityId' => $model->getIdentityId()
        ));
        $ret = $table->update($data, array('id' => $model->getRegistrationId()));
        
        return $ret;
    }
    
    public function remove(ModelAbstract $model) {
        throw new \Exception('TODO');
    }
    
    public function getPaginatorAdapter(array $params) {
        throw new \Exception('TODO');
    }
    
    public function findByIdentityId($identityId) {
        $ret = $this->getTableGateway($this->tableName)->select(array(
            'identityId' => $identityId,
        ));
        
        $row = $ret->current();
        if(!$row) {
            return null;
        }
        
        $model = IdentityRegistration::fromArray($row);
        return $model;
    }
    
    public function getModelPrototype() {
        return clone $this->modelPrototype;
    }

    public function setModelPrototype(ModelAbstract $modelPrototype) {
        $this->modelPrototype = $modelPrototype;
    }


}