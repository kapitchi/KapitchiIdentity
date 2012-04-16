<?php

namespace KapitchiIdentity\Model\Mapper;

use ZfcBase\Mapper\ModelMapperInterface;

interface RegistrationInterface extends ModelMapperInterface {
    public function findByIdentityId($id);
}