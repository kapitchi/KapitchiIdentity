<?php

namespace KapitchiIdentity\Form\AuthCredential;

use ZfcBase\Form\Form;

class Login extends Form {
    
    public function init() {
        $this->addElement('text', 'username', array(
            'label' => 'Username',
            'required' => true,
        ));
        $this->addElement('password', 'password', array(
            'label' => 'Password',
            'required' => true,
        ));
    }
    
}