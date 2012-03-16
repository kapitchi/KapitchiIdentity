<?php

namespace KapitchiIdentity\Form;

use ZfcBase\Form\ProvidesEventsForm;

class AuthCredentialLogin extends ProvidesEventsForm {
    
    public function init() {
        $this->addElement('text', 'username', array(
            'label' => 'Username',
        ));
        $this->addElement('password', 'password', array(
            'label' => 'Password',
        ));
    }
    
}