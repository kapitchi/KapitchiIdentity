<?php

namespace KapitchiIdentity\Model\Mapper;

use ZfcBase\Mapper\ModelMapperInterface;

interface IdentityRegistrationInterface extends ModelMapperInterface {
    public function findByIdentityId($id);
}