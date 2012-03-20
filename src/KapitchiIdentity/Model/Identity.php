<?php

namespace KapitchiIdentity\Model;

use     Zend\Acl\Resource as AclResource,
        KapitchiBase\Model\ModelAbstract;

class Identity extends ModelAbstract {
    protected $id;
    protected $created;
    protected $ownerId;
    
    public function getResourceId() {
        $resourceId = 'KapitchiIdentity/Identity';
        if($this->getId()) {
            $resourceId .= '/' . $this->getId();
        }
        
        return $resourceId;
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