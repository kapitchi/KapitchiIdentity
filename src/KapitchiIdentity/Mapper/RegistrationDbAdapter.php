<?php

namespace KapitchiIdentity\Mapper;

use KapitchiEntity\Mapper\EntityDbAdapterMapper;

class RegistrationDbAdapter extends EntityDbAdapterMapper implements RegistrationInterface {
    
    public function findByIdentityId($id) {
        $items = $this->getPaginatorAdapter(array(
            'identityId' => $id
        ))->getItems(0, 1);
        return current($items);
    }
    
}