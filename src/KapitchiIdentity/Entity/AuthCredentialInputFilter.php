<?php
namespace KapitchiIdentity\Entity;

use KapitchiBase\InputFilter\EventManagerAwareInputFilter;

/**
 *
 * @author Matus Zeman <mz@kapitchi.com>
 */
class AuthCredentialInputFilter extends EventManagerAwareInputFilter
{
    public function __construct()
    {
        $this->add(array(
            'name'       => 'username',
            'required'   => true,
            'validators' => array(
                array(
                    'name'    => 'StringLength',
                    'options' => array(
                        'min' => 2,
                    ),
                ),
            ),
            'filters'   => array(
                array('name' => 'StringTrim'),
            ),
        ));
        
        $this->add(array(
            'name'       => 'password',
            'required'   => false,
        ));
        $this->add(array(
            'name'       => 'passwordConfirm',
            'required'   => false,
        ));
    }
}