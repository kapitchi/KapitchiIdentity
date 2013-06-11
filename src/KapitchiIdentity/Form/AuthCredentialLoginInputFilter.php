<?php
/**
 * Kapitchi Zend Framework 2 Modules (http://kapitchi.com/)
 *
 * @copyright Copyright (c) 2012-2013 Kapitchi Open Source Team (http://kapitchi.com/open-source-team)
 * @license   http://opensource.org/licenses/LGPL-3.0 LGPL 3.0
 */

namespace KapitchiIdentity\Form;

/**
 *
 * @author Matus Zeman <mz@kapitchi.com>
 */
class AuthCredentialLoginInputFilter extends \Zend\InputFilter\InputFilter
{
    public function __construct()
    {
        $this->add(array(
            'name'       => 'username',
            'required'   => true,
            'filters'   => array(
                array('name' => 'StringTrim'),
            ),
        ));
        
        $this->add(array(
            'name'       => 'password',
            'required'   => true,
        ));
    }
}