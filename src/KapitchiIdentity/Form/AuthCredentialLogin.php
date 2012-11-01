<?php

namespace KapitchiIdentity\Form;

use KapitchiBase\Form\EventManagerAwareForm;

class AuthCredentialLogin extends EventManagerAwareForm {
    
    public function __construct($name = null)
    {
        parent::__construct($name);
        
        $this->setLabel('Credential');
        
        $this->add(array(
            'name' => 'username',
            'type' => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => $this->translate('Username'),
            ),
            'attributes' => array(
            ),
        ));
        
        $this->add(array(
            'name' => 'password',
            'type' => 'Zend\Form\Element\Password',
            'options' => array(
                'label' => $this->translate('Password'),
            ),
            'attributes' => array(
            ),
        ));
        
    }
    
}