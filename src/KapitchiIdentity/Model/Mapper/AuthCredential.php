<?php

namespace KapitchiIdentity\Model\Mapper;

interface AuthCredential {
    public function findByUsername($username);
}