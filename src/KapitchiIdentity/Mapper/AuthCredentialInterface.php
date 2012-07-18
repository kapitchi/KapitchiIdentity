<?php

namespace KapitchiIdentity\Mapper;

use KapitchiEntity\Mapper\EntityMapperInterface;

interface AuthCredentialInterface extends EntityMapperInterface {
    public function findByUsername($username);
    public function findByIdentityId($id);
}