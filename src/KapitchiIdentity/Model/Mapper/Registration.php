<?php

namespace KapitchiIdentity\Model\Mapper;

use ZfcBase\Mapper\ModelMapper;

interface Registration extends ModelMapper {
    public function findByIdentityId($id);
}