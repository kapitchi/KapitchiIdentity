<?php
namespace KapitchiIdentity\Controller;

use KapitchiEntity\Controller\AbstractEntityController;

class IdentityController extends AbstractEntityController
{
    public function getIndexUrl()
    {
        return $this->url()->fromRoute('identity/identity', array(
            'action' => 'index'
        ));
    }

    public function getUpdateUrl($entity)
    {
        return $this->url()->fromRoute('identity/identity', array(
            'action' => 'update', 'id' => $entity->getId()
        ));
    }
    
}
