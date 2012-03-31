<?php

namespace KapitchiIdentity\Model;

use ZfcBase\Model\ModelAbstract;

class Registration extends ModelAbstract {
    //protected $id;
    protected $identityId;
    protected $requestIp;
    protected $created;
    
//    public function getId() {
//        return $this->id;
//    }
//    
//    public function setId($id) {
//        $this->id = $id;
//    }
    
    public function getCreated() {
        return $this->created;
    }

    public function setCreated($created) {
        $this->created = $created;
    }

    public function getIdentityId() {
        return $this->identityId;
    }

    public function setIdentityId($identityId) {
        $this->identityId = $identityId;
    }

    public function getRequestIp() {
        return $this->requestIp;
    }

    public function setRequestIp($requestIp) {
        $this->requestIp = $requestIp;
    }


}