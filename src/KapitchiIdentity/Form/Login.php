<?php

namespace KapitchiIdentity\Form;

use KapitchiBase\Form\EventManagerAwareForm;

class Login extends EventManagerAwareForm
{
    public function __construct($name = null)
    {
        parent::__construct($name);
        
        $this->setValidationGroup(array());
    }
}