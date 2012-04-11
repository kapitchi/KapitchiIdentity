<?php

namespace KapitchiIdentity\Plugin\ZfcAcl;

use ZfcAcl\Exception\UnauthorizedException,
    RuntimeException as NotArrayAccessResource;

class ResourceLoader {
    protected $resourceLoaderDefMapper;
    protected $roleIdTemplate = 'identity/%d';
    protected $identityRoleService;
    protected $aclService;
    
    public function onLoadResource($e) {
        $resource = $e->getParam('resource');
        $acl = $e->getParam('acl');

        if(!$resource instanceof \ArrayAccess) {
            return;
            //throw new NotArrayAccessResource("Resource must implement ArrayAccess");
        }
        
        $resourceClass = get_class($resource);
        
        $parentRole = $this->getIdentityRoleService()->getCurrentStaticRole();
                
        $def = $this->getResourceLoaderDefMapper()->findByResourceClass($resourceClass);
        if($def) {
            foreach($def->getAllowRules() as $identityProperty => $privileges) {
                $roleId = sprintf($this->roleIdTemplate, $resource[$identityProperty]);
                if(!$acl->hasRole($roleId)) {
                    $acl->addRole($roleId, $parentRole);
                }
                if(!$acl->hasResource($resource)) {
                    $acl->addResource($resource, $def->getParentResource());
                }
                
                $acl->allow($roleId, $resource, $privileges);
            }
        }
    }
    
    //setters/getters
    public function getResourceLoaderDefMapper() {
        return $this->resourceLoaderDefMapper;
    }

    public function setResourceLoaderDefMapper($resourceLoaderDefMapper) {
        $this->resourceLoaderDefMapper = $resourceLoaderDefMapper;
    }

    public function getAclService() {
        return $this->aclService;
    }

    public function setAclService($aclService) {
        $this->aclService = $aclService;
    }

    public function getIdentityRoleService() {
        return $this->identityRoleService;
    }

    public function setIdentityRoleService($identityRoleService) {
        $this->identityRoleService = $identityRoleService;
    }


}