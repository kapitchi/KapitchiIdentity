<?php

namespace KapitchiIdentity\Form;

use KapitchiBase\Form\EventManagerAwareForm;

class Registration extends EventManagerAwareForm {
    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);
        $this->addElement('text', 'requestIp', array(
            'label' => $this->translate("Your IP"),
            'readonly' => true,
        ));
    }
}