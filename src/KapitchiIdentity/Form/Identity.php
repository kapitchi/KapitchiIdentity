<?php

namespace KapitchiIdentity\Form;

use KapitchiBase\Form\EventManagerAwareForm;

class Identity extends EventManagerAwareForm {
    
    public function __construct()
    {
        parent::__construct();
        
        $this->add(array(
            'name' => 'displayName',
            'type' => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => 'Display name',
            ),
            'attributes' => array(
            ),
        ));
        
        $this->add(array(
            'name' => 'created',
            'type' => 'Zend\Form\Element\Datetime',
            'options' => array(
                'label' => 'Vytvoreny',
            ),
            'attributes' => array(
            ),
        ));
    }
}