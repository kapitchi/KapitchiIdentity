<?php
/**
 * Kapitchi Zend Framework 2 Modules (http://kapitchi.com/)
 *
 * @copyright Copyright (c) 2012-2013 Kapitchi Open Source Team (http://kapitchi.com/open-source-team)
 * @license   http://opensource.org/licenses/LGPL-3.0 LGPL 3.0
 */

namespace KapitchiIdentity\Form;

use KapitchiBase\InputFilter\EventManagerAwareInputFilter;

class LoginInputFilter extends EventManagerAwareInputFilter
{
    public function __construct()
    {
        $this->add(array(
            'name'       => 'method',
            'required'   => true,
        ));
        $this->setValidationGroup(array());
    }
    
    public function isValid()
    {
        $method = $this->getValue('method');
        if(!empty($method)) {
            $this->setValidationGroup($method);
        }
        
        return parent::isValid();
    }
}