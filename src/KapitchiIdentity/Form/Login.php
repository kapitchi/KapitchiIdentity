<?php

namespace KapitchiIdentity\Form;

use KapitchiBase\Form\EventManagerAwareForm;

class Login extends EventManagerAwareForm {
    
    public function __construct()
    {
        
        $this->triggerInitEvent();
    }
    
}