<?php
/**
 * Kapitchi Zend Framework 2 Modules (http://kapitchi.com/)
 *
 * @copyright Copyright (c) 2012-2013 Kapitchi Open Source Team (http://kapitchi.com/open-source-team)
 * @license   http://opensource.org/licenses/LGPL-3.0 LGPL 3.0
 */

namespace KapitchiIdentity\Controller;

use KapitchiEntity\Controller\EntityContoller;

class IdentityController extends EntityContoller
{
<<<<<<< HEAD
    public function getIndexUrl()
    {
        return $this->url()->fromRoute('identity/identity', array(
            'action' => 'index'
        ));
    }
=======

    public function lookupAction()
    {
        return array(
            'iframeCallerId' => $this->getRequest()->getQuery()->get('iframeCallerId')
        );
    }
    
>>>>>>> ce50feb53c6a8bfb2bd2769674206de616937981
}
