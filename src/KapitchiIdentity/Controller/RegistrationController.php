<?php
/**
 * Kapitchi Zend Framework 2 Modules (http://kapitchi.com/)
 *
 * @copyright Copyright (c) 2012-2013 Kapitchi Open Source Team (http://kapitchi.com/open-source-team)
 * @license   http://opensource.org/licenses/LGPL-3.0 LGPL 3.0
 */

namespace KapitchiIdentity\Controller;

use Zend\View\Model\ViewModel;
use KapitchiEntity\Controller\EntityController;

class RegistrationController extends EntityController
{
    public function registerAction() {
        $form = $this->getEntityForm();
        $request = $this->getRequest();
        
        $viewModel = new ViewModel();
        $viewModel->form = $form;
        
        $params = array(
            'viewModel' => $viewModel,
        );
        
        $res = $this->getEventManager()->trigger('register.pre', $this, $params, function($ret) {
            return $ret instanceof Response;
        });
        $result = $res->last();
        if($result instanceof Response) {
            return $result;
        }
        
        if($request->isPost()) {
            $postData = $request->getPost()->toArray();
            $form->setData($postData);
            if($form->isValid()) {
                $registerResult = $this->getEntityService()->register($form->getData());
                $params['registerEvent'] = $registerResult;
                $res = $this->getEventManager()->trigger('register.post', $this, $params, function($ret) {
                    return $ret instanceof Response;
                });
                $result = $res->last();
                if($result instanceof Response) {
                    return $result;
                }
            }
        }
        
        //TODO
        $form->add(array(
            'name' => 'submit',
            'type' => 'Zend\Form\Element\Submit',
            'options' => array(
                'label' => $this->translate('Register'),
            ),
            'attributes' => array(
                'value' => $this->translate('Register')
            ),
        ));
        
        return $viewModel;
    }
    
    /**
     * Dummy for form labels
     * @param string $msg
     * @return string
     */
    protected function translate($msg)
    {
        return $msg;
    }
    
    protected function attachDefaultListeners()
    {
        parent::attachDefaultListeners();
        $em = $this->getEventManager();
        $em->attach('register.post', function($e) {
            return $e->getTarget()->redirect()->toRoute('home');
        });
    }

}