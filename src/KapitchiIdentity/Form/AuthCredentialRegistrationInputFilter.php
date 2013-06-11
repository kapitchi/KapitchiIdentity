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
class AuthCredentialRegistrationInputFilter extends \Zend\InputFilter\InputFilter
{
    public function __construct(\KapitchiIdentity\Validator\AuthCredentialUsernameExists $usernameExistsValidator)
    {
        $this->add(array(
            'name'       => 'username',
            'required'   => true,
            'filters'   => array(
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                $usernameExistsValidator
            )
        ));
        
        $this->add(array(
            'name'       => 'password',
            'required'   => true,
        ));
        
        $this->add(array(
            'name'       => 'passwordConfirm',
            'required'   => true,
            'validators' => array(
                array(
                    'name'    => 'Identical',
                    'options' => array(
                        'token' => 'password',
                    ),
                ),
            ),
            
        ));
    }
}