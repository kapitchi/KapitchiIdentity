<?php
/**
 * Kapitchi Zend Framework 2 Modules (http://kapitchi.com/)
 *
 * @copyright Copyright (c) 2012-2013 Kapitchi Open Source Team (http://kapitchi.com/open-source-team)
 * @license   http://opensource.org/licenses/LGPL-3.0 LGPL 3.0
 */

namespace KapitchiIdentity\Form;

use KapitchiBase\Form\EventManagerAwareForm;

class Login extends EventManagerAwareForm
{
    public function __construct($name = null)
    {
        parent::__construct($name);
        
        $this->add(array(
            'name' => 'method',
            'type' => 'Zend\Form\Element\Radio',
            'options' => array(
                'value_options' => array(),
                'label' => 'Authentication method',
            ),
        ));
    }
    
    /**
     * @return bool
     */
    public function isValid()
    {
        $this->setValidationGroup('method');
                
        if(!empty($this->data['method'])) {
            $this->setValidationGroup($this->data['method']);
        }
        
        return parent::isValid();
    }
}