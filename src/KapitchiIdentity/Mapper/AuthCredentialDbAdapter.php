<?php

namespace KapitchiIdentity\Mapper;

use KapitchiEntity\Mapper\EntityDbAdapterMapper;

class AuthCredentialDbAdapter extends EntityDbAdapterMapper implements AuthCredentialInterface {
    
    public function findByIdentityId($id) {
        $items = $this->getPaginatorAdapter(array(
            'identityId' => $id
        ))->getItems(0, 1);
        return current($items);
    }
    
    public function findByUsername($username) {
        $items = $this->getPaginatorAdapter(array(
            'username' => $username
        ))->getItems(0, 1);
        return current($items);
    }

}