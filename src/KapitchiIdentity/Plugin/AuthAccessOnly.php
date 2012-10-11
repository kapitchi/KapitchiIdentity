<?php
namespace KapitchiIdentity\Plugin;

use Zend\EventManager\EventInterface,
    KapitchiApp\PluginManager\PluginInterface;

/**
 *
 * @author Matus Zeman <mz@kapitchi.com>
 */
class AuthAccessOnly implements PluginInterface
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
        return '[KapitchiIdentity] Authenticated users only';
    }

    public function getVersion()
    {
        return '0.1';
    }
    
    protected $loginRoute = 'identity/auth/login';
    
    public function onBootstrap(EventInterface $e)
    {
        $app = $e->getApplication();
        $em = $app->getEventManager();
        $sharedEm = $em->getSharedManager();
        $sm = $app->getServiceManager();
        $instance = $this;
        
        $sharedEm->attach('Zend\Stdlib\DispatchableInterface', \Zend\Mvc\MvcEvent::EVENT_DISPATCH, function($e) use ($sm, $instance) {
            if($e->getRouteMatch()->getMatchedRouteName() == $instance->getLoginRoute()) {
                return;
            }
            
            $authService = $sm->get('KapitchiIdentity\Service\Auth');
            if(!$authService->hasIdentity()) {
                return $e->getTarget()->plugin('redirect')->toRoute($instance->getLoginRoute());
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