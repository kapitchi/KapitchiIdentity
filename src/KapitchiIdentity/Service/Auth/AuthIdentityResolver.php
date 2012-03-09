<?php

namespace KapitchiIdentity\Service\Auth;

interface AuthIdentityResolver {
    public function resolveAuthIdentity($id);
}