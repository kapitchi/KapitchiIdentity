<?php

namespace KapitchiIdentity\Model\Mapper;

use ZfcBase\Mapper\ModelMapperInterface;

interface AuthCredentialInterface extends ModelMapperInterface {
    public function findByUsername($username);
    public function findByIdentityId($id);
}