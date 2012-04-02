<?php

namespace KapitchiIdentity;

use Zend\Module\Manager,
    Zend\Mvc\AppContext as Application,
    Zend\EventManager\StaticEventManager,
    Zend\EventManager\EventDescription as Event,
    Zend\Mvc\MvcEvent as MvcEvent,
    KapitchiBase\Module\ModuleAbstract;

class Module extends ModuleAbstract {
    
    public function bootstrap(Manager $moduleManager, Application $app) {
        $locator      = $app->getLocator();
        
        $events = StaticEventManager::getInstance();
        $instance = $this;
        
        //auth-identity
        $events->attach('KapitchiIdentity\Service\Identity', 'persist.pre', function($e) use($locator) {
            $service = $locator->get('KapitchiIdentity\Service\Auth');
            $identity = $e->getParam('model');
            
            try {
                $id = $service->getLocalIdentityId();
                if($id !== null) {
                    $identity->setOwnerId($id);
                }
            //user might not be logged in
            } catch(\Exception $e) {
                
            }
        });
        
        $events->attach('ZfcAcl\Service\Acl', 'loadResource', function($e) use($locator) {
            $resource = $e->getParam('resource');
            $acl = $e->getParam('acl');
            if($resource instanceof Model\Identity) {
                
                $specs = array(
                    'parent_role' => 'user',
                    'parent_resource' => 'KapitchiIdentity\Model\Identity',
                    'rules' => array(
                        'xxx' => array(
                            'identityProperty' => 'ownerId',
                            'privilege' => array('get', 'persist', 'remove')
                        ),
                        'yyy' => array(
                            'identityProperty' => 'id',
                            'privilege' => array('get')
                        ),
                    )
                );
                
                foreach($specs['rules'] as $rule) {
                    $roleId = '' . $resource[$rule['identityProperty']];
                    if(!$acl->hasRole($roleId)) {
                        $acl->addRole($roleId, $specs['parent_role']);
                    }
                    if(!$acl->hasResource($resource)) {
                        $acl->addResource($resource, $specs['parent_resource']);
                    }
                    $acl->allow($roleId, $resource, $rule['privilege']);
                }
            }
        });
        
    }
    
    public function getDir() {
        return __DIR__;
    }
    
    public function getNamespace() {
        return __NAMESPACE__;
    }
    
}
