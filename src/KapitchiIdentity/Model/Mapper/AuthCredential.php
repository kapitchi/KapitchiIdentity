<?php

namespace KapitchiIdentity\Model\Mapper;

interface AuthCredential extends \ZfcBase\Mapper\ModelMapper {
    public function findByUsername($username);
    public function findByIdentityId($id);
}