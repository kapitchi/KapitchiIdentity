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
        
        $this->add(array(
            'name' => 'id',
            'type' => 'Zend\Form\Element\Hidden',
            'options' => array(
                'label' => $this->translate('ID'),
            ),
            'attributes' => array(
            ),
        ));
        
//        $this->add(array(
//            'name' => 'displayName',
//            'type' => 'Zend\Form\Element\Text',
//            'options' => array(
//                'label' => $this->translate('Display name'),
//            ),
//            'attributes' => array(
//            ),
//        ));
    }
}