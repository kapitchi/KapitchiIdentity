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
    protected $identityForm;
    protected $identityService;
    
    public function __construct(Module $module) {
        $this->module = $module;
    }
    
    public function meAction() {
        //$authService = $this->getLocator()->get('KapitchiIdentity\Service\Auth');
        //$id = $authService->getLocalIdentityId();
        
        $identityService = $this->getIdentityService();
        $identity = $identityService->get(array('priKey' => 1), true);
        var_dump($identity);
        
        $paginator = $identityService->getPaginator();
        $paginator->setCurrentPageNumber(4);
        $paginator->setItemCountPerPage(3);
        $items = $paginator->getCurrentItems();
        foreach($items as $item) {
            var_dump($item);
        }
        exit;
        
        $model = new ViewModel(
            array('identity' => $identity,
        ));
    }
    
    public function indexAction() {
        
    }
    
    public function editAction() {
        $form = $this->getIdentityForm();
        
        $request = $this->getRequest();
        if($request->isPost()) {
            $postData = $request->post()->toArray();
            if($form->isValid($postData)) {
                $ret = $this->getIdentityService()->persist($form->getValues());
                $values = $ret['model']->toArray();
                $form->populate($values);
            }
        }
        
        $form->addElement('submit', 'submit', array(
            'label' => 'Save'
        ));
        
        return array(
            'identityForm' => $form
        );
    }
    
    public function deleteAction() {
        $id = $this->getQueryIdentityId();
        
    }
    
    //helper methods
    protected function getQueryIdentityId() {
        $id = $this->getRequest()->query()->id;
        if(empty($id)) {
            throw new NoIdException('No id param');
        }
        
        return $id;
    }
    
    //listeners
    protected function attachDefaultListeners()
    {
        parent::attachDefaultListeners();
        $events = $this->events();
        //$events->attach('logout.post', array($this, 'logoutPost'));
        //$events->attach('authenticate.post', array($this, 'loginPost'));
    }
    
    //getters/setters
    public function getModule() {
        return $this->module;
    }

    public function setModule(Module $module) {
        $this->module = $module;
    }

    public function getIdentityForm() {
        return $this->identityForm;
    }

    public function setIdentityForm($identityForm) {
        $this->identityForm = $identityForm;
    }

    public function getIdentityService() {
        return $this->identityService;
    }

    public function setIdentityService($identityService) {
        $this->identityService = $identityService;
    }


}