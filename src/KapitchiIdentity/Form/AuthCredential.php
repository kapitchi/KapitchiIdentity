<?php
namespace KapitchiIdentity\Form;

use KapitchiBase\Form\EventManagerAwareForm;

class AuthCredential extends EventManagerAwareForm {
    
    public function __construct($name = null)
    {
        parent::__construct($name);
        
        $this->setLabel('Username/password');
        
        $this->add(array(
            'name' => 'id',
            'options' => array(
                'label' => 'ID',
            ),
            'attributes' => array(
                'type' => 'hidden'
            ),
        ));
        
        $this->add(array(
            'name' => 'username',
            'options' => array(
                'label' => 'Username',
            ),
            'attributes' => array(
                'type' => 'text'
            ),
        ));
        
        $this->add(array(
            'name' => 'password',
            'options' => array(
                'label' => 'Password',
            ),
            'attributes' => array(
                'type' => 'password'
            ),
        ));
        
        $this->add(array(
            'name' => 'passwordConfirm',
            'options' => array(
                'label' => 'Confirm password',
            ),
            'attributes' => array(
                'type' => 'password'
            ),
        ));
    }
    
}