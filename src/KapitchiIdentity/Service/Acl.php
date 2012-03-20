<?php

namespace KapitchiIdentity\Service;

use     Zend\EventManager\EventCollection,
        Zend\EventManager\EventManager,
        Zend\Acl\Acl as ZendAcl,
        Zend\Acl\Resource,
        Zend\Acl\Resource\GenericResource,
        Zend\Acl\Role,
        Zend\Acl\Role\GenericRole,
        Zend\EventManager\Event,
        Zend\Acl\Exception\InvalidArgumentException as ZendAclInvalidArgumentException,
        KapitchiBase\Service\ServiceAbstract,
        KapitchiIdentity\Module,
        KapitchiIdentity\Model\Mapper\AclLoader,
        InvalidArgumentException as NoStringResourceException;

class Acl extends ServiceAbstract {
    const ROLE_GUEST = 'guest';
    const ROLE_AUTH = 'auth';
    const ROLE_USER = 'user';
    const ROLE_ADMIN = 'admin';
    
    protected $module;
    protected $acl;
    protected $aclLoader;
    
    /**
     * TODO XXX there is a bug in DI while injecting a module works with constructor only!
     * @param Module $module 
     */
    public function __construct(Module $module) {
        $this->setModule($module);
    }
    
    public function isAllowed($resource = null, $privilege = null) {
        $acl = $this->getAcl();
        $roleId = $this->getRoleId();
        
        //mz: we want to get resource object here at all times
        if(!$resource instanceof Resource) {
            $result = $this->triggerEvent('resolveResource', array(
                'resource' => $resource
            ), function($ret) {
                return $ret instanceof Resource;
            });
            $resolvedResource = $result->last();
            if($resolvedResource instanceof Resource) {
                $resource = $resolvedResource;
            }
            else {
                if(!is_string($resource)) {
                    throw new NoStringResourceException("Resource needs to be Resource instance or string");
                }
                $resource = new GenericResource($resource);
            }
        }
        
        if(!$acl->hasResource($resource)) {
            
            //mz: this is your chance to load resource ACL up!
            $this->triggerEvent('loadResource', array(
                'acl' => $acl,
                'roleId' => $roleId,
                'resource' => $resource,
                'privilage' => $privilege
            ));
            
            //mz: we don't want to cache dynamic resources - this should be removed?
            //persist (possibly) update ACL into cache mechanism e.g. session etc.
//            $this->triggerEvent('cacheAcl', array(
//                'acl' => $acl,
//                'roleId' => $roleId,
//                'resource' => $resource,
//                'privilage' => $privilege
//            ));
        }
        
        try {
            $ret = $acl->isAllowed($roleId, $resource, $privilege);
            
            return $ret;
        } catch(ZendAclInvalidArgumentException $e) {
            //mz: in case there is still no resource/role
            return false;
        }
    }
    
    public function invalidateCache() {
        $this->triggerEvent('invalidateCache', array(
            'roleId' => $this->getRoleId(),
        ));
        
        $this->acl = null;
    }
    
    /**
     * @return Zend\Acl\Role
     */
    public function getRole() {
        $result = $this->events()->trigger('getRole', $this, array(), function($ret) {
            return $ret instanceof Role;
        });
        $role = $result->last();
        if(!$role instanceof Role) {
            $role = new GenericRole(self::ROLE_GUEST);
        }
        
        return $role;
    }
    
    protected function getRoleId() {
        return $this->getRole()->getRoleId();
    }
    
    protected function getAcl() {
        if($this->acl === null) {
            $roleId = $this->getRoleId();
            $result = $this->events()->trigger('getAcl', $this, array(
                'roleId' => $roleId,
            ), function($ret) {
                return ($ret instanceof ZendAcl);
            });
            $acl = $result->last();
            //is there some plugin which returns acl (e.g. cached in the session?)
            if ($acl instanceof ZendAcl) {
                $this->acl = $acl;
                return $this->acl;
            }
            
            $acl = $this->getLocator()->get('Zend\Acl\Acl');
            $this->triggerEvent('loadStaticAcl', array(
                'acl' => $acl,
                'roleId' => $roleId,
            ));
            $this->triggerEvent('cacheStaticAcl', array(
                'acl' => $acl,
                'roleId' => $roleId,
            ));
            
            $this->acl = $acl;
        }
        
        return $this->acl;
    }
    
    //event listeners
    public function getCacheSessionAcl(Event $e) {
        //TODO DI!
        $mapper = $this->getLocator()->get('KapitchiIdentity\Model\Mapper\AclCacheSession');
        $acl = $mapper->loadByRoleId($e->getParam('role'));
        return $acl;
    }
    
    public function persistCacheSessionAcl(Event $e) {
        //TODO DI!
        $mapper = $this->getLocator()->get('KapitchiIdentity\Model\Mapper\AclCacheSession');
        return $mapper->persist($e->getParam('acl'), $e->getParam('role'));
    }
    
    public function invalidateCacheSession(Event $e) {
        //TODO DI!
        $mapper = $this->getLocator()->get('KapitchiIdentity\Model\Mapper\AclCacheSession');
        $mapper->invalidate($e->getParam('role'));
    }
    
    /**
     * Retrieve the event manager
     *
     * Lazy-loads an EventManager instance if none registered.
     * 
     * @return EventCollection
     */
    public function events()
    {
        if (!$this->events instanceof EventCollection) {
            $this->setEventManager(new EventManager(array(__CLASS__, get_class($this))));
            $this->attachDefaultListeners();
        }
        return $this->events;
    }
    
    protected function attachDefaultListeners() {
        $events = $this->events();
        
        //load default roles
//        $events->attach('loadStaticAcl', function($e) {
//            $acl = $e->getParam('acl');
//            //init default roles
//            $acl->addRole(\KapitchiIdentity\Service\Acl::ROLE_GUEST);
//            $acl->addRole(\KapitchiIdentity\Service\Acl::ROLE_AUTH);
//            $acl->addRole(\KapitchiIdentity\Service\Acl::ROLE_USER);
//            $acl->addRole(\KapitchiIdentity\Service\Acl::ROLE_ADMIN);
//        });
        
        //AclLoaderMapper
        $aclLoader = $this->getAclLoader();
        $events->attach('loadStaticAcl', function($e) use ($aclLoader) {
            if($aclLoader instanceof AclLoader) {
                $aclLoader->loadAclByRoleId($e->getParam('acl'), $e->getParam('roleId'));
            }
        });
        
        if($this->getModule()->getOption('acl.enable_cache', false)) {
            $events->attach('getAcl', array($this, 'getCacheSessionAcl'));
            $events->attach('cacheStaticAcl', array($this, 'persistCacheSessionAcl'));
            $events->attach('invalidateCache', array($this, 'invalidateCacheSessionCache'));
        }
    }
    
    public function setModule(Module $module) {
        $this->module = $module;
    }
    
    public function getModule() {
        return $this->module;
    }
    
    public function getAclLoader() {
        return $this->aclLoader;
    }

    public function setAclLoader(AclLoader $aclLoader) {
        $this->aclLoader = $aclLoader;
    }

}