<?php
/**
 * Kapitchi Zend Framework 2 Modules (http://kapitchi.com/)
 *
 * @copyright Copyright (c) 2012-2013 Kapitchi Open Source Team (http://kapitchi.com/open-source-team)
 * @license   http://opensource.org/licenses/LGPL-3.0 LGPL 3.0
 */

namespace KapitchiIdentity\Form;

use KapitchiBase\Form\EventManagerAwareForm;

class Identity extends EventManagerAwareForm {
    
    public function __construct()
    {
        parent::__construct();
        
        $this->add(array(
            'name' => 'id',
            'type' => 'Zend\Form\Element\Hidden',
            'options' => array(
                'label' => $this->translate('ID'),
            ),
            'attributes' => array(
            ),
        ));
        
        $this->add(array(
            'name' => 'displayName',
            'type' => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => $this->translate('Display name'),
            ),
            'attributes' => array(
            ),
        ));
        
        $this->add(array(
            'name' => 'created',
            'type' => 'Zend\Form\Element\DateTime',
            'options' => array(
                'label' => $this->translate('Created'),
            ),
            'attributes' => array(
            ),
        ));
        
        $this->add(array(
            'name' => 'authEnabled',
            'type' => 'Zend\Form\Element\Checkbox',
            'options' => array(
                'label' => $this->translate('Authetication enabled'),
            ),
        ));
    }
}