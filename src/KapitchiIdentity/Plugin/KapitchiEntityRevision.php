<?php
/**
 * Kapitchi Zend Framework 2 Modules (http://kapitchi.com/)
 *
 * @copyright Copyright (c) 2012-2013 Kapitchi Open Source Team (http://kapitchi.com/open-source-team)
 * @license   http://opensource.org/licenses/LGPL-3.0 LGPL 3.0
 */

namespace KapitchiIdentity\Plugin;

use Zend\EventManager\EventInterface,
    KapitchiApp\PluginManager\PluginInterface;

/**
 *
 * @author Matus Zeman <mz@kapitchi.com>
 */
class KapitchiEntityRevision implements PluginInterface
{
    public function getAuthor()
    {
        return 'Matus Zeman';
    }

    public function getDescription()
    {
        return 'TODO';
    }

    public function getName()
    {
        return '[KapitchiIdentity] Sets logged in identity ID as revisionOwnerId';
    }

    public function getVersion()
    {
        return '0.1';
    }
    
    public function onBootstrap(EventInterface $e)
    {
        $app = $e->getApplication();
        $em = $app->getEventManager();
        $sharedEm = $em->getSharedManager();
        $sm = $app->getServiceManager();
        $instance = $this;
        
        $sharedEm->attach('KapitchiEntity\Service\RevisionService', 'persist', function($e) use ($sm, $instance) {
            $authService = $sm->get('KapitchiIdentity\Service\Auth');
            if($authService->hasIdentity()) {
                $id = $authService->getLocalIdentityId();
                $entity = $e->getParam('entity');
                $entity->setRevisionOwnerId($id);
            }
        }, 100);
        
    }

    public function getLoginRoute()
    {
        return $this->loginRoute;
    }

    public function setLoginRoute($loginRoute)
    {
        $this->loginRoute = $loginRoute;
    }

}