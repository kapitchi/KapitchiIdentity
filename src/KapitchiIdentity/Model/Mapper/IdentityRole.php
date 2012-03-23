<?php

namespace KapitchiIdentity\Model\Mapper;

interface IdentityRole {
    public function findByIdentityId($id);
}