<?php
namespace KapitchiIdentity\Form;

use KapitchiBase\Form\EventManagerAwareForm;

class AuthCredential extends EventManagerAwareForm {
    
    public function __construct($name = null)
    {
        parent::__construct($name);
        
        $this->add(array(
            'name' => 'id',
            'type' => 'Zend\Form\Element\Hidden',
            'options' => array(
                'label' => $this->translate('ID'),
            ),
            'attributes' => array(
            ),
        ));
        
        $this->add(array(
            'name' => 'enabled',
            'type' => 'Zend\Form\Element\Checkbox',
            'options' => array(
                'label' => $this->translate('Credential auth. enabled'),
            ),
        ));
        //default is enabled
        $this->get('enabled')->setValue(true);
        
        $this->add(array(
            'name' => 'username',
            'type' => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => $this->translate('Username'),
            ),
            'attributes' => array(
            ),
        ));
        
        $this->add(array(
            'name' => 'password',
            'type' => 'Zend\Form\Element\Password',
            'options' => array(
                'label' => $this->translate('Password'),
            ),
            'attributes' => array(
            ),
        ));
        
        $this->add(array(
            'name' => 'passwordConfirm',
            'type' => 'Zend\Form\Element\Password',
            'options' => array(
                'label' => $this->translate('Confirm password'),
            ),
            'attributes' => array(
            ),
        ));
    }
    
}