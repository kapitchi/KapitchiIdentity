<?php

namespace KapitchiIdentity\Entity;

class Identity 
{
    protected $id;
    protected $created;
    protected $authEnabled;
    protected $displayName;
    protected $ownerId;
    
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
    
    public function getAuthEnabled()
    {
        return $this->authEnabled;
    }

    public function setAuthEnabled($authEnabled)
    {
        $this->authEnabled = $authEnabled;
    }

    public function getDisplayName()
    {
        return $this->displayName;
    }

    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
    }

    public function getOwnerId() {
        return $this->ownerId;
    }

    public function setOwnerId($ownerId) {
        $this->ownerId = $ownerId;
    }


}