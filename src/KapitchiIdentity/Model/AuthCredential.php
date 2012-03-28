<?php
namespace KapitchiIdentity\Model;

use ZfcBase\Model\ModelAbstract;

class AuthCredential extends ModelAbstract implements IdentityAware {
    protected $id;
    protected $identityId;
    protected $username;
    protected $password;
    
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

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function getIdentityId() {
        return $this->identityId;
    }
    
    public function setIdentityId($identityId) {
        $this->identityId = $identityId;
    }
}