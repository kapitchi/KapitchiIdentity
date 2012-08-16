<?php
namespace KapitchiIdentity\Authentication;

interface AuthIdentityResolverInterface {
    public function resolveAuthIdentity($id);
}