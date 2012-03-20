<?php

namespace KapitchiIdentity\Model\Mapper;

use     Zend\Acl\Acl as ZendAcl,
        InvalidArgumentException;

class AclLoaderConfig implements AclLoader {
    protected $config;
    
    public function loadAclByRoleId(ZendAcl $acl, $roleId) {
        $config = $this->getConfig();
        //mz: do nothing if config is empty/null
        if(empty($config)) {
            return;
        }
        
        if(!empty($config['resources'])) {
            $this->loadResources($acl, $config['resources']);
        }
        
        if(!empty($config['roles'])) {
            $this->loadRoles($acl, $config['roles']);
        }
        
        if(!empty($config['rules'])) {
            $this->loadRules($acl, $config['rules']);
        }
    }
    
    protected function loadResources(ZendAcl $acl, array $resources, $parent = null) {
        foreach($resources as $key => $value) {
            if(is_array($value)) {
                $acl->addResource($key, $parent);
                $this->loadResources($acl, $value, $key);
            }
            else {
                $acl->addResource($key, $parent);
            }
        }
    }
    
    /**
     * This can load roles in simple structure only for now
     * array(
     *      'myuser' => 'user'
     *      'superuser' => array('admin', 'user','auth', 'guest')
     * )
     * 
     * @author mz
     * @param ZendAcl $acl
     * @param array $roles 
     */
    protected function loadRoles(ZendAcl $acl, array $roles) {
        foreach($roles as $role => $parentRoles) {
            $acl->addRole($role, $parentRoles);
        }
    }
    
    /**
     * TODO needs some checking!!!
     * @param ZendAcl $acl
     * @param array $rules 
     */
    protected function loadRules(ZendAcl $acl, array $rules) {
        if(isset($rules['allow'])) {
            $allowRules = $rules['allow'];
        }   
        if(isset($rules['deny'])) {
            $denyRules = $rules['deny'];
        }
        
        foreach($allowRules as $rule) {
            $privileges = null;
            if(count($rule) == 3) {
                list($roles, $resources, $privileges) = $rule;
            }
            else if(count($rule) == 2) {
                list($roles, $resources) = $rule;
            }
            else {
                throw new InvalidArgumentException("What is this rule definition about? " . print_r($rule, true));
            }
            $acl->allow($roles, $resources, $privileges);
        }
        
        foreach($denyRules as $rule) {
            $privileges = null;
            if(count($rule) == 3) {
                list($roles, $resources, $privileges) = $rule;
            }
            else if(count($rule) == 2) {
                list($roles, $resources) = $rule;
            }
            else {
                throw new InvalidArgumentException("What is this rule definition about? " . print_r($rule, true));
            }
            $acl->deny($roles, $resources, $privileges);
        }
    }
    
    //setters/getters
    public function getConfig() {
        return $this->config;
    }

    public function setConfig($config) {
        if(!is_array($config)) {
            throw new InvalidArgumentException("We accept array only for now!");
        }
        
        $this->config = $config;
    }

}