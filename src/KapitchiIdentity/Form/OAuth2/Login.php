<?php

namespace KapitchiIdentity\Form\OAuth2;

use ZfcBase\Form\Form;

class Login extends Form {
    
    public function init() {
        $this->addElement('text', 'endpoint', array(
            'label' => 'Endpoint',
        ));
    }
    
}