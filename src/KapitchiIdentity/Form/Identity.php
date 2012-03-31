<?php

namespace KapitchiIdentity\Form;

use ZfcBase\Form\Form;

class Identity extends Form {
    public function init() {
        $this->addElement('hidden', 'id');
        $this->addElement('text', 'created');
    }
}