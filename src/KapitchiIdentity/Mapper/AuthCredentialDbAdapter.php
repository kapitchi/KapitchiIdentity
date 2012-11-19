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
    
    protected function updateArray(array $data)
    {
        //mz: we just want to make sure passwordHash is not set
        //it has to be set using persistPasswordHash()
        unset($data['passwordHash']);
        return parent::updateArray($data);
    }

    public function updatePasswordHash($id, $passwordHash)
    {
        $data = array(
            'id' => $id,
            'passwordHash' => $passwordHash
        );
        return parent::updateArray($data);
    }

}