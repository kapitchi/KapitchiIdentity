<?php

namespace KapitchiIdentity\Form;

use ZfcBase\Form\ProvidesEventsForm;

class Identity extends ProvidesEventsForm {
    public function init() {
        $this->addElement('hidden', 'id');
        $this->addElement('text', 'created');
    }
}