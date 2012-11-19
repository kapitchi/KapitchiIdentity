<?php
namespace KapitchiIdentity\Entity;

class AuthCredential
{
    protected $id;
    protected $identityId;
    protected $enabled;
    protected $username;
    protected $passwordHash;
    
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }
    
    public function getEnabled()
    {
        return $this->enabled;
    }

    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }
    
    public function getUsername() {
        return $this->username;
    }

    public function setUsername($username) {
        $this->username = $username;
    }
    
    public function getIdentityId() {
        return $this->identityId;
    }
    
    public function setIdentityId($identityId) {
        $this->identityId = $identityId;
    }
    
    public function getPasswordHash()
    {
        return $this->passwordHash;
    }

    public function setPasswordHash($passwordHash)
    {
        $this->passwordHash = $passwordHash;
    }
    
}