<?php

namespace KapitchiIdentity\Form\OAuth2;

use ZfcBase\Form\Form;

class Login extends Form {
    
    public function init() {
        $this->addElement('radio', 'provider', array(
            'multioptions' => array(
                'google' => 'Google',
                'facebook' => 'Facebook',
            ),
        ));
        $this->addElement('text', 'endpoint', array(
            'label' => 'Endpoint',
        ));
    }
    
}