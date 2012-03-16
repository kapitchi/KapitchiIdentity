<?php

namespace KapitchiIdentity\Model\Mapper;

use KapitchiBase\Mapper\DbAdapterMapper,
    KapitchiIdentity\Model\AuthCredential,
    KapitchiIdentity\Model\Mapper\AuthCredential as AuthCredentialMapper;

class AuthCredentialZendDb extends DbAdapterMapper implements AuthCredentialMapper {
    protected $tableName = 'auth_credential';
    
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