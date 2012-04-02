<?php

namespace KapitchiIdentity\Model\Mapper;

use ZfcBase\Mapper\DbAdapterMapper,
    KapitchiIdentity\Model\AuthCredential,
    ZfcBase\Model\ModelAbstract,
    KapitchiIdentity\Model\Mapper\AuthCredential as AuthCredentialMapper;

class AuthCredentialDbAdapter extends DbAdapterMapper implements AuthCredentialMapper {
    protected $tableName = 'identity_auth_credential';
    
    public function findByPriKey($key) {
        
    }
    
    public function persist(ModelAbstract $model) {
        $table = $this->getTableGateway($this->tableName, true);
        $data = $this->toScalarValueArray($model);
        if($model->getId()) {
            unset($data['id']);
            $ret = $table->update($data, array('id' => $model->getId()));
        }
        else {
            $ret = $table->insert($data);
            $model->setId((int)$table->getLastInsertId());
        }
        
        return $ret;
    }
    
    public function remove(ModelAbstract $model) {
        
    }
    
    public function getPaginatorAdapter(array $params) {
        
    }
    
    public function findByIdentityId($id) {
        $ret = $this->getTableGateway($this->tableName)->select(array(
            'identityId' => $id
        ));
        
        $row = $ret->current();
        if(!$row) {
            return null;
        }
        
        $model = AuthCredential::fromArray($row->getArrayCopy());
        return $model;
    }
    
    public function findByUsername($username) {
        $ret = $this->getTableGateway($this->tableName)->select(array(
            'username' => $username
        ));
        
        $row = $ret->current();
        if(!$row) {
            return null;
        }
        
        $model = AuthCredential::fromArray($row->getArrayCopy());
        return $model;
    }
}