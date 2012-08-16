<?php
namespace KapitchiIdentity\Model;

interface AuthIdentityInterface {
    public function getIdentity();
    public function getLocalIdentityId();
}