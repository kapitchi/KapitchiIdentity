<?php
/**
 * Kapitchi Zend Framework 2 Modules (http://kapitchi.com/)
 *
 * @copyright Copyright (c) 2012-2013 Kapitchi Open Source Team (http://kapitchi.com/open-source-team)
 * @license   http://opensource.org/licenses/LGPL-3.0 LGPL 3.0
 */

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