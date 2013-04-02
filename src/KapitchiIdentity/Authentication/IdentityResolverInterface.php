<?php
namespace KapitchiIdentity\Authentication;

interface IdentityResolverInterface {
    public function resolveIdentityId($authId);
}