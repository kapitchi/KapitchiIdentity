<?php

namespace KapitchiIdentity\Model;

use ZfcBase\Model\ModelAbstract;

class IdentityRegistration extends ModelAbstract {
    protected $id;
    protected $registrationId;
    protected $identityId;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getRegistrationId() {
        return $this->registrationId;
    }

    public function setRegistrationId($registrationId) {
        $this->registrationId = $registrationId;
    }
    
    public function getIdentityId() {
        return $this->identityId;
    }

    public function setIdentityId($identityId) {
        $this->identityId = $identityId;
    }

}