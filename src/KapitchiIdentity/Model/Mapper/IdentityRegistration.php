<?php

namespace KapitchiIdentity\Model\Mapper;

use ZfcBase\Mapper\ModelMapper;

interface IdentityRegistration extends ModelMapper {
    public function findByIdentityId($id);
}