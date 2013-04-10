<?php
/**
 * Kapitchi Zend Framework 2 Modules (http://kapitchi.com/)
 *
 * @copyright Copyright (c) 2012-2013 Kapitchi Open Source Team (http://kapitchi.com/open-source-team)
 * @license   http://opensource.org/licenses/LGPL-3.0 LGPL 3.0
 */

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
    
    public function lookupAction()
    {
        return array(
            'iframeCallerId' => $this->getRequest()->getQuery()->get('iframeCallerId')
        );
    }
    
}
