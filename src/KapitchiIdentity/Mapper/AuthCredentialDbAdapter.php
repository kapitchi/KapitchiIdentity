<?php
/**
 * Kapitchi Zend Framework 2 Modules (http://kapitchi.com/)
 *
 * @copyright Copyright (c) 2012-2013 Kapitchi Open Source Team (http://kapitchi.com/open-source-team)
 * @license   http://opensource.org/licenses/LGPL-3.0 LGPL 3.0
 */

namespace KapitchiIdentity\Mapper;

use KapitchiEntity\Mapper\EntityDbAdapterMapper;

class AuthCredentialDbAdapter extends EntityDbAdapterMapper implements AuthCredentialInterface {
    
    public function findByIdentityId($id) {
        $item = $this->getPaginatorAdapter(array(
            'identityId' => $id
        ))->getItems(0, 1)->current();
        return $item;
    }
    
    public function findByUsername($username) {
        $item = $this->getPaginatorAdapter(array(
            'username' => $username
        ))->getItems(0, 1)->current();
        return $item;
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