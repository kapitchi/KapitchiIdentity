<?php
namespace KapitchiIdentity\Controller;

use KapitchiEntity\Controller\AbstractEntityController;

class IdentityController extends AbstractEntityController
{
    /**
     * TODO XXX needed here as there is a problem with SM/DI - prepareElement() is called
     */
    public function getEntityForm()
    {
        $form = new \KapitchiIdentity\Form\Identity();
        return $form;
    }
    
    public function getCurrentEntityId() {
        return $this->getEvent()->getRouteMatch()->getParam('id');
    }

    public function getCurrentPageNumber()
    {
        return $this->getRequest()->getQuery()->page;
    }

    public function getIndexUrl()
    {
        return $this->url()->fromRoute('kapitchi-identity/identity', array(
            'action' => 'index'
        ));
    }

    public function getUpdateUrl($entity)
    {
        return $this->url()->fromRoute('kapitchi-identity/identity', array(
            'action' => 'update', 'id' => $entity->getId()
        ));
    }
    
}
