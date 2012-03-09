<?php

namespace KapitchiIdentity\Model\Mapper;

use KapitchiIdentity\Model\Mapper\Identity as IdentityMapper,
    KapitchiIdentity\Model\Identity;

class IdentityTest implements IdentityMapper, \KapitchiBase\Mapper\Transactional {
    
    public function beginTransaction() {
        
    }
    
    public function commit() {
        
    }
    
    public function persist(Identity $model) {
        $model->setId(2);
        $model->setOwnerId(1);
        
        return $model;
    }
    
    public function remove(Identity $model) {
        var_dump($model);
        exit;
    }
}