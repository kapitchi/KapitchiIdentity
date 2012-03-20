<?php

namespace KapitchiIdentity\Controller;

use Zend\Authentication\Adapter as AuthAdapter,
        Zend\Stdlib\ResponseDescription as Response,
        Zend\View\Model\ViewModel as ViewModel,
        KapitchiBase\View\Model\Table as TableViewModel,
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
        //mz: TODO I believe getLocalIdentityId should throw exception if not logged in!
        $id = $this->getLocator()->get('KapitchiIdentity\Service\Auth')->getLocalIdentityId();
        if(empty($id)) {
            throw new \Exception("User is not logged in!");
        }
        
        $identityService = $this->getIdentityService();
        $identity = $identityService->get(array('priKey' => $id), true);
        
        $model = new ViewModel(
            array('identity' => $identity,
        ));
        
        return $model;
    }
    
    public function indexAction() {
        $routeMatch = $this->getEvent()->getRouteMatch();
        $page = $routeMatch->getParam('page', 1);
        
        $paginator = $this->getIdentityService()->getPaginator();
        $paginator->setItemCountPerPage(10);
        $paginator->setCurrentPageNumber($page);
        
        return new TableViewModel(array(
            'paginator' => $paginator
        ));
    }
    
    public function createAction() {
        $form = $this->getIdentityForm();
        
        $request = $this->getRequest();
        if($request->isPost()) {
            $postData = $request->post()->toArray();
            if($form->isValid($postData)) {
                $ret = $this->getIdentityService()->persist($form->getValues());
                return $this->redirect()->toRoute('KapitchiIdentity/identity/update', array('id' => $ret['model']->getId()));
            }
        }
        
        $form->addElement('submit', 'submit', array(
            'label' => 'Create'
        ));
        
        $viewModel = new ViewModel(array(
            'identityForm' => $form
        ));
        $viewModel->setTemplate('identity/update');
        return $viewModel;
    }
    
    public function updateAction() {
        $id = $this->getIdentityId();
        if(empty($id)) {
            throw new NoIdException("No id");
        }
        
        $form = $this->getIdentityForm();
        
        $request = $this->getRequest();
        if($request->isPost()) {
            $postData = $request->post()->toArray();
            if($form->isValid($postData)) {
                $ret = $this->getIdentityService()->persist($form->getValues());
            }
        }
        
        $identity = $this->getIdentityService()->get(array(
            'priKey' => $id
        ), true);
        $form->populate($identity->toArray());
        
        $form->addElement('submit', 'submit', array(
            'label' => 'Update'
        ));
        
        $viewModel = new ViewModel(array(
            'identityForm' => $form
        ));
        $viewModel->setTemplate('identity/update');
        return $viewModel;
    }
    
    public function deleteAction() {
        $id = $this->getQueryIdentityId();
        
    }
    
    //helper methods
    protected function getIdentityId() {
        $routeMatch = $this->getEvent()->getRouteMatch();
        $id = $routeMatch->getParam('id');
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