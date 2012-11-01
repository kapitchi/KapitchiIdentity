<?php

namespace KapitchiIdentity\Form;

use KapitchiBase\Form\EventManagerAwareForm;

class Registration extends EventManagerAwareForm {
    
    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);
        
        $this->addElement('text', 'username', array(
            'label' => $this->translate('Username'),
            'required' => true,
        ));
        $this->addElement('password', 'password', array(
            'label' => $this->translate('Password'),
            'required' => true,
        ));
        $this->addElement('password', 'passwordConfirm', array(
            'label' => $this->translate('Confirm password'),
            'required' => true,
        ));
    }
    
}