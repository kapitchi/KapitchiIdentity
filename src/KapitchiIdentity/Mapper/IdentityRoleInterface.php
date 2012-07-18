<?php

namespace KapitchiIdentity\Model\Mapper;

use KapitchiEntity\Mapper\EntityMapperInterface;

interface IdentityRoleInterface extends EntityMapperInterface {
    public function findByIdentityId($id);
}