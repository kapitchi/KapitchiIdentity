<?php

namespace KapitchiIdentity\Model\Mapper;

use KapitchiBase\Mapper\DbAdapterMapper,
    KapitchiIdentity\Model\AuthCredential,
    ZfcBase\Model\ModelAbstract,
    KapitchiIdentity\Model\Mapper\AuthCredential as AuthCredentialMapper;

class AuthCredentialDbAdapter extends DbAdapterMapper implements AuthCredentialMapper {
    protected $tableName = 'auth_credential';
    
    public function findByPriKey($key) {
        
    }
    
    public function persist(ModelAbstract $model) {
        if($model->getId()) {
            $ret = $this->update($model);
        }
        else {
            $ret = $this->insert($model);
        }
        
        return $ret;
    }
    
    protected function insert(ModelAbstract $model) {
        $identityTable = $this->getTableGateway($this->tableName);
        
        $data = $model->toArray();
        $ret = $identityTable->insert($data);
        $model->setId((int)$identityTable->getLastInsertId());
        
        return $ret;
    }
    
    protected function update(ModelAbstract $model) {
        $table = $this->getTableGateway($this->tableName);
        
        $data = $model->toArray();
        unset($data['id']);
        $ret = $table->update($data, array('id' => $model->getId()));
        
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