<?php

namespace KapitchiIdentity\Form\AuthCredential;

use ZfcBase\Form\Form;

class Login extends Form {
    
    public function init() {
        $this->addElement('text', 'username', array(
            'label' => 'Username',
        ));
        $this->addElement('password', 'password', array(
            'label' => 'Password',
        ));
    }
    
}