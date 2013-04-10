<?php
/**
 * Kapitchi Zend Framework 2 Modules (http://kapitchi.com/)
 *
 * @copyright Copyright (c) 2012-2013 Kapitchi Open Source Team (http://kapitchi.com/open-source-team)
 * @license   http://opensource.org/licenses/LGPL-3.0 LGPL 3.0
 */

namespace KapitchiIdentity\Controller;

use Zend\Authentication\Adapter as AuthAdapter,
    Zend\Stdlib\ResponseDescription as Response,
    Zend\View\Model\ViewModel as ViewModel,
    KapitchiBase\View\Model\Table as TableViewModel,
    KapitchiIdentity\Module as Module,
    Zend\Mvc\Controller\ActionController as ZendActionController,
    RunTimeException as NoIdException;

class ProfileController extends ZendActionController {
    protected $module;
    protected $identityService;
    
    public function __construct(Module $module) {
        $this->module = $module;
    }
    
    public function meAction() {
        $id = $this->getLocator()->get('KapitchiIdentity\Service\Auth')->getLocalIdentityId();
        
        $identityService = $this->getIdentityService();
        $identity = $identityService->get(array('priKey' => $id), true);
        
        $model = new ViewModel(
            array('identity' => $identity,
        ));
        
        return $model;
    }
    
    //listeners
    protected function attachDefaultListeners()
    {
        parent::attachDefaultListeners();
        $events = $this->events();
    }
    
    //getters/setters
    public function getModule() {
        return $this->module;
    }

    public function setModule(Module $module) {
        $this->module = $module;
    }

    public function getIdentityService() {
        return $this->identityService;
    }

    public function setIdentityService($identityService) {
        $this->identityService = $identityService;
    }

}