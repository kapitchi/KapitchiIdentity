<?php

namespace KapitchiIdentity\Model\Mapper;

use KapitchiBase\Mapper\ModelMapper;

interface IdentityRole extends ModelMapper {
    public function findByIdentityId($id);
}