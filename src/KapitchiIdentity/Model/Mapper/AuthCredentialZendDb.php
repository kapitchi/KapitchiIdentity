<?php

namespace KapitchiIdentity\Model\Mapper;

use KapitchiBase\Mapper\DbAdapterMapper,
    KapitchiIdentity\Model\AuthCredential;

class AuthCredentialZendDb extends DbAdapterMapper {
    public function findByUsername($username) {
        $ret = $this->getTableGateway('auth_credential')->select(array(
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