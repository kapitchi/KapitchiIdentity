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
class KapitchiLog implements PluginInterface
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
        return '[KapitchiIdentity] Logs events';
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
        
        $sharedEm->attach('KapitchiIdentity\Service\Auth', 'authenticate.invalid', function($e) use ($sm, $instance) {
            $logService = $sm->get('KapitchiLog\Service\LogIndex');
            $result = $e->getParam('result');
            $logService->persistLog('identity-authenticate-invalid', array(
                'remoteIp' => $_SERVER['REMOTE_ADDR'],
                'identity' => $result->getIdentity(),
                'messages' => $result->getMessages(),
            ));
        });
        $sharedEm->attach('KapitchiIdentity\Service\Auth', 'authenticate.valid', function($e) use ($sm, $instance) {
            $logService = $sm->get('KapitchiLog\Service\LogIndex');
            $id = $e->getParam('authIdentity');
            $result = $e->getParam('result');
            $logService->persistLog('identity-authenticate-valid', array(
                'remoteIp' => $_SERVER['REMOTE_ADDR'],
                'identity' => $result->getIdentity(),
                'messages' => $result->getMessages(),
                'identityId' => $id->getLocalIdentityId()
            ));
        });
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