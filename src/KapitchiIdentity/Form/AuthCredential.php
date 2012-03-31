<?php

namespace KapitchiIdentity\Form;

use ZfcBase\Form\Form;

class AuthCredential extends Form {
    
    public function init() {
        $this->addElement('hidden', 'id');
        $this->addElement('text', 'username', array(
            'label' => 'Username',
        ));
        $this->addElement('password', 'password', array(
            'label' => 'Password',
        ));
        $this->addElement('password', 'passwordConfirm', array(
            'label' => 'Confirm password',
        ));
    }
    
}