<?php

namespace KapitchiIdentity;

use Zend\Module\Manager,
    Zend\EventManager\StaticEventManager,
    Zend\Module\Consumer\AutoloaderProvider,
    Zend\Module\Consumer\LocatorRegistered,
    Zend\EventManager\EventDescription as Event,
    Zend\Mvc\MvcEvent as MvcEvent;

class Module implements AutoloaderProvider, LocatorRegistered
{
    public function init(Manager $moduleManager)
    {
        $events = StaticEventManager::getInstance();
        $events->attach('bootstrap', 'bootstrap', array($this, 'bootstrap'));
    }
    
    public function bootstrap($e) {
        $app          = $e->getParam('application');
        $locator      = $app->getLocator();
        
        //route protector test
        /*$app->events()->attach('route', function(MvcEvent $e) use($locator) {
            $routeName = $e->getRouteMatch()->getMatchedRouteName();
            
            $aclService = $locator->get('KapitchiIdentity\Service\Acl');
            $aclService->invalidateCache();
            $ret = $aclService->isAllowed('route/' . $routeName);
            if(!$ret) {
                $e->setError('error-controller-cannot-dispatch');
            }
            
        }, -10);
        */
        
        $events = StaticEventManager::getInstance();
        
        $events->attach('KapitchiIdentity\Controller\AuthController', 'authenticate.init',
                array($locator->get('KapitchiIdentity\Service\Auth\Credential'), 'onInit'));
        
        $events->attach('KapitchiIdentity\Service\Auth', 'clearIdentity.post', function($e) use($locator) {
            $acl = $locator->get('KapitchiIdentity\Service\Acl');
            $acl->invalidateCache();
        });
        
        $events->attach('KapitchiIdentity\Service\Acl', 'getRole', function($e) use($locator) {
            $authService = $locator->get('KapitchiIdentity\Service\Auth');
            if(!$authService->hasIdentity()) {
                return;
            }

            $authIdentity = $authService->getIdentity();
//            $roleId = $authIdentity->getRoleId();
//            if(empty($roleId)) {
//                throw new \Exception("User has got no role, why???");
//            }

            return $authIdentity;
        });
            
        $events->attach('KapitchiIdentity\Controller\AuthController', 'authenticate.init', function(Event $e) use ($locator) {
            $acl = $locator->get('KapitchiIdentity\Service\Acl');
        });
        
        $events->attach('KapitchiIdentity\Service\Acl', 'loadResource', function(Event $e) {
            $acl = $e->getParam('acl');
            $resource = $e->getParam('resource');
            
            //XXX this allows everything for user account
            $acl->addResource($resource);
            $acl->allow('user', $resource, null);
        });
    }
    
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
    /**
     * Returns module option value.
     * Dot character is used to separate sub arrays.
     * 
     * Example:
     * array(
     *      'option1' => 'this is my option 1'
     *      'option2' => array(
     *          'key1' => 'sub key1',
     *          'key2' => 'sub key2',
     *      )
     * )
     * 
     * $module->getOption('option1');
     * Returns: (string) "This is my option 1"
     *
     * $module->getOption('option2');
     * Returns: array(
     *          'key1' => 'sub key1',
     *          'key2' => 'sub key2',
     *      )
     * 
     * $module->getOption('option2.key1');
     * Returns: (string) "sub key1"
     * 
     * @param string $option
     * @param mixed $default
     * @return mixed 
     */
    public function getOption($option, $default = null) {
        $options = $this->getOptions();
        $optionArr = explode('.', $option);
        
        $option = $this->_getOption($options, $optionArr, $default, $option);
        return $option;
    }
    
    private function _getOption(array $options, array $option, $default, $origOption) {
        $currOption = array_shift($option);
        if(array_key_exists($currOption, $options)) {
            if(count($option) >= 1) {
                return $this->_getOption($options[$currOption], $option, $default, $origOption);
            }
            
            return $options[$currOption];
        }
        
        if($default !== null) {
            return $default;
        }
        
        throw new \InvalidArgumentException("Option '$origOption' is not set");
    }
    
    public function getOptions() {
        $config = $this->getConfig();
        if(empty($config[__NAMESPACE__]['options'])) {
            return array();
        }
        return $config[__NAMESPACE__]['options'];
    }
    
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
}
