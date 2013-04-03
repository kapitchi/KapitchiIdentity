<?php
namespace KapitchiIdentity\Model;

interface AuthIdentityInterface {
    public function setIdentity($identity);
    public function getIdentity();
    public function setId($id);
    public function getId();
    public function setSessionId($sessionId);
    public function getSessionId();
    public function isEqual(AuthIdentityInterface $identity);
}