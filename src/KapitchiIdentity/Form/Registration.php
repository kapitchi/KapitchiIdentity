<?php

namespace KapitchiIdentity\Form;

use ZfcBase\Form\Form;

class Registration extends Form {
    public function init() {
        $this->addElement('text', 'requestIp', array(
            'label' => "Your IP",
            'readonly' => true,
        ));
    }
}