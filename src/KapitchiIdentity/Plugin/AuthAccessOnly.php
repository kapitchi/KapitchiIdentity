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
    
    protected $loginRouteOptions = array(
        'name' => 'identity/auth/login'
    );
    
    public function onBootstrap(EventInterface $e)
    {
        $app = $e->getApplication();
        $em = $app->getEventManager();
        $sharedEm = $em->getSharedManager();
        $sm = $app->getServiceManager();
        $instance = $this;
        
        $app->getEventManager()->attach(\Zend\Mvc\MvcEvent::EVENT_ROUTE, function($e) use ($sm, $instance) {
            if($e->getRouteMatch()->getMatchedRouteName() == $instance->getLoginRouteName()) {
                return;
            }
            
            $authService = $sm->get('KapitchiIdentity\Service\Auth');
            if(!$authService->hasIdentity()) {
                $router = $e->getRouter();
                $options = $instance->getLoginRouteOptions();
                
                $url = $router->assemble(array(), $options);
                
                $response = $e->getResponse();
                $response->setStatusCode(\Zend\Http\Response::STATUS_CODE_307);
                $response->getHeaders()->addHeaderLine('Location', $url);
                
                return $response;
            }
        }, -1);
        
    }

    public function getLoginRouteOptions()
    {
        return $this->loginRouteOptions;
    }

    public function setLoginRouteOptions($loginRouteOptions)
    {
        $this->loginRouteOptions = $loginRouteOptions;
    }

    public function getLoginRouteName()
    {
        $spec = $this->getLoginRouteOptions();
        if(!isset($spec['name'])) {
            throw new \RuntimeException("Route spec does not define 'name'");
        }
        
        return $spec['name'];
    }

}