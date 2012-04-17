<?php
namespace KapitchiIdentity\Model;

use ZfcBase\Model\ModelAbstract;

class AuthCredential extends ModelAbstract {
    protected $id;
    protected $identityId;
    protected $username;
    protected $passwordHash;
    
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getUsername() {
        return $this->username;
    }

    public function setUsername($username) {
        $this->username = $username;
    }
    
    public function getPasswordHash() {
        return $this->passwordHash;
    }

    public function setPasswordHash($passwordHash) {
        $this->passwordHash = $passwordHash;
    }
    
    public function getIdentityId() {
        return $this->identityId;
    }
    
    public function setIdentityId($identityId) {
        $this->identityId = $identityId;
    }
}