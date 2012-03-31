<?php

namespace KapitchiIdentity\Form;

use ZfcBase\Form\Form;

class IdentityRole extends Form {
    public function init() {
        $this->addElement('hidden', 'identityId');
        $this->addElement('select', 'roleId', array('multiOptions' => array(
            'user' => "User",
            'admin' => "Admin",
        )));
    }
}