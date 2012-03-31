<?php

namespace KapitchiIdentity\Form;

use ZfcBase\Form\Form;

class AuthCredentialLogin extends Form {
    
    public function init() {
        $this->addElement('text', 'username', array(
            'label' => 'Username',
        ));
        $this->addElement('password', 'password', array(
            'label' => 'Password',
        ));
    }
    
}