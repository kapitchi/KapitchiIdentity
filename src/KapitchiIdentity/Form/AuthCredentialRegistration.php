<?php
/**
 * Kapitchi Zend Framework 2 Modules (http://kapitchi.com/)
 *
 * @copyright Copyright (c) 2012-2013 Kapitchi Open Source Team (http://kapitchi.com/open-source-team)
 * @license   http://opensource.org/licenses/LGPL-3.0 LGPL 3.0
 */

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