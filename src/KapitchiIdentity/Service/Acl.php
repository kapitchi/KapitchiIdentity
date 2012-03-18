<?php

namespace KapitchiIdentity\Service;

use     Zend\EventManager\EventCollection,
        Zend\EventManager\EventManager,
        Zend\Acl\Acl as ZendAcl,
        Zend\Acl\Role,
        Zend\Acl\Role\GenericRole,
        Zend\EventManager\Event,
        Zend\Acl\Exception\InvalidArgumentException as ZendAclInvalidArgumentException,
        KapitchiBase\Service\ServiceAbstract,
        KapitchiIdentity\Module;

class Acl extends ServiceAbstract {
    const ROLE_GUEST = 'guest';
    const ROLE_AUTH = 'auth';
    const ROLE_USER = 'user';
    
    protected $module;
    protected $acl;
    
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
        
        if(!$acl->hasResource($resource)) {
            
            //this is your chance to load resource ACL up!
            $this->triggerEvent('loadResource', array(
                'acl' => $acl,
                'roleId' => $roleId,
                'resource' => $resource,
                'privilage' => $privilege
            ));
            
            //persist (possibly) update ACL into cache mechanism e.g. session etc.
            $this->triggerEvent('cacheAcl', array(
                'acl' => $acl,
                'roleId' => $roleId,
                'resource' => $resource,
                'privilage' => $privilege
            ));
        }
        
        try {
            $ret = $acl->isAllowed($roleId, $resource, $privilege);
            
            return $ret;
        } catch(ZendAclInvalidArgumentException $e) {
            //in case there is still no resource/role
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
    protected function getRole() {
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
            $result = $this->events()->trigger('loadAcl', $this, array(), function($ret) {
                return ($ret instanceof ZendAcl);
            });
            $acl = $result->last();
            if (!$acl instanceof ZendAcl) {
                //TODO proper exception
                throw new \Exception("No ACL can't be loaded");
            }
            
            $this->acl = $acl;
        }
        
        return $this->acl;
    }
    
    public function loadDefaultAcl(Event $e) {
        $acl = $this->getLocator()->get('Zend\Acl\Acl');
        
        //init default roles
        $acl->addRole(self::ROLE_GUEST);
        $acl->addRole(self::ROLE_AUTH);
        $acl->addRole(self::ROLE_USER);
        
        return $acl;
    }
    
    
    //event listeners
    public function loadSessionAcl(Event $e) {
        $mapper = $this->getLocator()->get('KapitchiIdentity\Model\Mapper\AclSession');
        $acl = $mapper->loadByRoleId($e->getParam('role'));
        return $acl;
    }
    
    public function persistSessionAcl(Event $e) {
        $mapper = $this->getLocator()->get('KapitchiIdentity\Model\Mapper\AclSession');
        return $mapper->persist($e->getParam('acl'), $e->getParam('role'));
    }
    
    public function invalidateSessionCache(Event $e) {
        $mapper = $this->getLocator()->get('KapitchiIdentity\Model\Mapper\AclSession');
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
        $events->attach('loadAcl', array($this, 'loadDefaultAcl'), -20);
        $events->attach('loadAcl', array($this, 'loadSessionAcl'), -10);
        
        if($this->getModule()->getOption('acl.enable_cache', false)) {
            $events->attach('cacheAcl', array($this, 'persistSessionAcl'), -10);
            $events->attach('invalidateCache', array($this, 'invalidateSessionCache'), -10);
        }
        
    }
    
    public function setModule(Module $module) {
        $this->module = $module;
    }
    
    public function getModule() {
        return $this->module;
    }
}