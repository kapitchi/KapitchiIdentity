<?php

namespace KapitchiIdentity\Form;

use ZfcBase\Form\Form;

class Registration extends Form {
    
    public function init() {
        $this->addElement('text', 'username', array(
            'label' => 'Username',
            'required' => true,
        ));
        $this->addElement('password', 'password', array(
            'label' => 'Password',
            'required' => true,
        ));
        $this->addElement('password', 'passwordConfirm', array(
            'label' => 'Confirm password',
            'required' => true,
        ));
    }
    
}