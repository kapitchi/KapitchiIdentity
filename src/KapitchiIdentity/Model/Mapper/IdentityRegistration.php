<?php

namespace KapitchiIdentity\Model\Mapper;

use ZfcBase\Mapper\ModelMapperInterface;

interface IdentityRegistration extends ModelMapperInterface {
    public function findByIdentityId($id);
}