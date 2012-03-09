<?php

namespace KapitchiIdentity\Model\Mapper;

use Zend\Acl\Acl as ZendAcl;

interface Acl {
    public function loadByRoleId($roleId);
    public function persist(ZendAcl $acl, $roleId);
    public function invalidate($roleId);
    
}