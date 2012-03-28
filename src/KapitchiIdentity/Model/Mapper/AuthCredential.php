<?php

namespace KapitchiIdentity\Model\Mapper;

interface AuthCredential extends \KapitchiBase\Mapper\ModelMapper {
    public function findByUsername($username);
    public function findByIdentityId($id);
}