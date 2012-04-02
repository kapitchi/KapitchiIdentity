<?php

namespace KapitchiIdentity\Form\AuthCredential;

use ZfcBase\Form\Form;

class Registration extends Form {
    
    public function init() {
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