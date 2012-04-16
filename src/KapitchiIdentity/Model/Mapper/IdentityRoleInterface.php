<?php

namespace KapitchiIdentity\Model\Mapper;

use ZfcBase\Mapper\ModelMapperInterface;

interface IdentityRoleInterface extends ModelMapperInterface {
    public function findByIdentityId($id);
}