<?php
/**
 * Kapitchi Zend Framework 2 Modules (http://kapitchi.com/)
 *
 * @copyright Copyright (c) 2012-2013 Kapitchi Open Source Team (http://kapitchi.com/open-source-team)
 * @license   http://opensource.org/licenses/LGPL-3.0 LGPL 3.0
 */

namespace KapitchiIdentity\Form;

use KapitchiBase\InputFilter\EventManagerAwareInputFilter;

class RegistrationInputFilter extends EventManagerAwareInputFilter
{
    public function __construct()
    {
        $this->add(array(
            'name'       => 'id',
            'required'   => false,
        ));
//        $this->add(array(
//            'name'       => 'displayName',
//            'required'   => true,
//            'validators' => array(
//                array(
//                    'name'    => 'StringLength',
//                    'options' => array(
//                        'min' => 2,
//                    ),
//                ),
//            ),
//            'filters'   => array(
//                array('name' => 'StringTrim'),
//            ),
//        ));
        
    }
}