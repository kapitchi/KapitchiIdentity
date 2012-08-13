<?php

namespace KapitchiIdentity\Form;

use KapitchiBase\Form\EventManagerAwareForm;

class Identity extends EventManagerAwareForm {
    
    public function __construct()
    {
        parent::__construct();
        
        $this->add(array(
            'name' => 'created',
            'options' => array(
                'label' => 'Vytvoreny',
            ),
            'attributes' => array(
                'type' => 'datetime'
            ),
        ));
    }
}