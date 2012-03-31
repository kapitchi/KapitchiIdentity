<?php

namespace KapitchiIdentity\Model\Mapper;

use ZfcBase\Mapper\ModelMapper;

interface IdentityRole extends ModelMapper {
    public function findByIdentityId($id);
}