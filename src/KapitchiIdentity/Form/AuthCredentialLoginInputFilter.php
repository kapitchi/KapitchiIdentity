<?php
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
            'required'   => false,
            'filters'   => array(
                array('name' => 'StringTrim'),
            ),
        ));
        
        $this->add(array(
            'name'       => 'password',
            'required'   => false,
        ));
    }
}