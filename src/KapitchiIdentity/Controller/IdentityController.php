<?php

namespace KapitchiIdentity\Controller;

use Zend\Authentication\Adapter as AuthAdapter,
        Zend\Stdlib\ResponseDescription as Response,
        Zend\View\Model\ViewModel as ViewModel,
        KapitchiIdentity\Module as Module,
        Zend\Mvc\Controller\ActionController as ZendActionController,
        RunTimeException as NoIdException;

class IdentityController extends ZendActionController {
    protected $module;
    
    public function __construct(Module $module) {
        $this->module = $module;
    }
    
    public function meAction() {
        
    }
    
    public function indexAction() {
        
    }
    
    public function editAction() {
        $form = $this->getIdentityForm();
        
        return array(
            'form' => $form
        );
    }
    
    public function deleteAction() {
        $id = $this->getQueryIdentityId();
        
    }
    
    public function getIdentityForm() {
        //TODO DI
        return $this->getLocator()->get('KapitchiIdentity\Form\Identity');
    }
    
    protected function getQueryIdentityId() {
        $id = $this->getRequest()->query()->id;
        if(empty($id)) {
            throw new NoIdException('No id param');
        }
        
        return $id;
    }
    
    protected function attachDefaultListeners()
    {
        parent::attachDefaultListeners();
        $events = $this->events();
        //$events->attach('logout.post', array($this, 'logoutPost'));
        //$events->attach('authenticate.post', array($this, 'loginPost'));
    }
    
}