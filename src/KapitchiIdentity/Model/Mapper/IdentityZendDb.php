<?php

namespace KapitchiIdentity\Model\Mapper;

use KapitchiIdentity\Model\Mapper\Identity as IdentityMapper,
    KapitchiIdentity\Model\Identity,
    KapitchiBase\Mapper\DbAdapterMapper;

class IdentityZendDb extends DbAdapterMapper implements IdentityMapper {
    protected $tableName = 'identity';
    private $identityTable;
    
    public function persist(Identity $model) {
        if($model->getId()) {
            $ret = $this->update($model);
        }
        else {
            $ret = $this->insert($model);
        }
        
        return $ret;
    }
    
    public function remove(Identity $model) {
        var_dump($model);
        exit;
    }
    
    protected function insert(Identity $model) {
        $identityTable = $this->getTableGateway($this->tableName, true);
        
        $data = $model->toArray();
        $ret = $identityTable->insert($data);
        $model->setId((int)$identityTable->getLastInsertId());
        
        return $ret;
    }
    
    protected function update(Identity $model) {
        $identityTable = $this->getTableGateway($this->tableName, true);
        
        $data = $model->toArray();
        unset($data['id']);
        $ret = $identityTable->update($data, array('id' => $model->getId()));
        
        return $ret;
    }
    
    protected function getIdentityTable() {
        if($this->identityTable === null) {
            $this->identityTable = $this->getTableGateway($this->tableName, true);
        }
        return $this->identityTable;
    }
}