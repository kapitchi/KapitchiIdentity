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
            'type' => 'Zend\Form\Element\Hidden',
            'options' => array(
                'label' => 'ID',
            ),
            'attributes' => array(
            ),
        ));
        
        $this->add(array(
            'name' => 'username',
            'type' => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => 'Username',
            ),
            'attributes' => array(
            ),
        ));
        
        $this->add(array(
            'name' => 'password',
            'type' => 'Zend\Form\Element\Password',
            'options' => array(
                'label' => 'Password',
            ),
            'attributes' => array(
            ),
        ));
        
        $this->add(array(
            'name' => 'passwordConfirm',
            'type' => 'Zend\Form\Element\Password',
            'options' => array(
                'label' => 'Confirm password',
            ),
            'attributes' => array(
            ),
        ));
    }
    
}