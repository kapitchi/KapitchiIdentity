<?php

namespace KapitchiIdentity\Form;

use Zend\Form\Form;

class Identity extends Form {
    public function __construct()
    {
        parent::__construct();
        
        $this->add(array(
            'name' => 'created',
            'options' => array(
                'label' => 'Vytvorena',
            ),
            'attributes' => array(
                'type' => 'text'
            ),
        ));
    }
}