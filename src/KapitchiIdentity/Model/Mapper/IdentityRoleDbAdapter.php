<?php

namespace KapitchiIdentity\Model\Mapper;

use Zend\Acl\Role\GenericRole,
    KapitchiBase\Mapper\DbAdapterMapper,
    KapitchiIdentity\Model\Mapper\IdentityRole as IdentityRoleMapper;

class IdentityRoleDbAdapter extends DbAdapterMapper implements IdentityRoleMapper {
    protected $tableName = 'identity';
    
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
        
        return new GenericRole($row->roleId);
    }
}