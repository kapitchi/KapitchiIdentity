<?php

namespace KapitchiIdentity\Mapper;

use KapitchiEntity\Mapper\EntityMapperInterface;

interface RegistrationInterface extends EntityMapperInterface {
    public function findByIdentityId($id);
}