<?php

namespace KapitchiIdentity\Form;

use KapitchiBase\Form\Form;

class AuthCredential extends Form {
    
    public function init() {
        $this->addElement('text', 'username', array(
            'label' => 'Username',
        ));
        $this->addElement('password', 'password', array(
            'label' => 'Password',
        ));
        $this->addElement('password', 'password2', array(
            'label' => 'Confirm password',
        ));
    }
    
}