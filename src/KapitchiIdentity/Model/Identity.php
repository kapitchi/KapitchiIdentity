<?php

namespace KapitchiIdentity\Model;

use Zend\Acl\Resource,
    ZfcBase\Model\ModelAbstract;

class Identity extends ModelAbstract implements Resource {
    protected $id;
    protected $created;
    protected $ownerId;
    
    public function getResourceId() {
        return __CLASS__ . '/' . $this->getId();
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function setId($id) {
        $this->id = $id;
    }
    
    public function getCreated() {
        return $this->created;
    }

    public function setCreated($created) {
        $this->created = $created;
    }

    public function getOwnerId() {
        return $this->ownerId;
    }

    public function setOwnerId($ownerId) {
        $this->ownerId = $ownerId;
    }


}