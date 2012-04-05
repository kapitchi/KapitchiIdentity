<?php

namespace KapitchiIdentity\Model\Mapper;

use ZfcBase\Model\ModelAbstract,
    ZfcBase\Mapper\DbAdapterMapper,
    KapitchiIdentity\Model\Mapper\IdentityRole as IdentityRoleMapper,
    KapitchiIdentity\Model\IdentityRole;

class IdentityRoleDbAdapter extends DbAdapterMapper implements IdentityRoleMapper {
    protected $tableName = 'identity';
    
    public function findByPriKey($key) {
        throw new \Exception('TODO');
    }
    
    public function persist(ModelAbstract $model) {
        $ret = $this->getTableGateway('role')->select(array(
            'roleId' => $model->getRoleId(),
        ));
        $row = $ret->current();
        $table = $this->getTableGateway($this->tableName, true);
        return $table->update(array('roleId' => $row->id), array('id' => $model->getIdentityId()));
    }
    
    public function remove(ModelAbstract $model) {
        throw new \Exception('TODO');
    }
    
    public function getPaginatorAdapter(array $params) {
        throw new \Exception('TODO');
    }
    
    public function findByIdentityId($identityId) {
        $ret = $this->getTableGateway($this->tableName)->select(array(
            'id' => $identityId,
        ));
        
        $row = $ret->current();
        if(!$row) {
            return null;
        }
        
        if(!$row->roleId) {
            return null;
        }
        
        $ret = $this->getTableGateway('role')->select(array(
            'id' => $row->roleId,
        ));
        
        $row = $ret->current();
        
        $role = new IdentityRole();
        $role->setRoleId($row->roleId);
        $role->setIdentityId($identityId);
        
        return $role;
    }
}