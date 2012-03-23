<?php

namespace KapitchiIdentity\Service;

interface AuthIdentityResolver {
    public function resolveAuthIdentity($id);
}