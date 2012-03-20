<?php

namespace KapitchiIdentity\Service\Acl;

use     Zend\Mvc\MvcEvent,
        KapitchiIdentity\Model\Mapper\AclLoader,
        Exception as NotAuthorizedException,
        Exception as NoRouteResourceFoundException;

class ProtectorRoute implements Protector {
    protected $routeResourceMap;
    protected $defaultRouteResource;
    protected $aclService;
    
    public function dispatch(MvcEvent $e) {
        $routeMatch = $e->getRouteMatch();
        $routeName = $routeMatch->getMatchedRouteName();
        $routeResource = $this->getRouteResource($routeName);
        if($routeResource === null) {
            //$routeResource = $this->getDefaultRouteResource();
            //mz: TODO what in this case???
            throw new NoRouteResourceFoundException("No route resource found");
        }
        
        $acl = $this->getAclService();
        if(!$acl->isAllowed($routeResource)) {
            $roleId = $acl->getRole()->getRoleId();
            throw new NotAuthorizedException("You ($roleId) are not allowed to access this route '$routeName' ($routeResource)");
        }
    }
    
    public function getRouteResource($routeName) {
        $routes = explode('/', $routeName);
        
        $routeMap = $this->getRouteResourceMap();
        
        $resolved = isset($routeMap['default']) ? $routeMap['default'] : null;
        while($route = array_shift($routes)) {
            if(isset($routeMap['child_map'][$route])) {
                $routeMap = $routeMap['child_map'][$route];
                if(isset($routeMap['default'])) {
                    $resolved = $routeMap['default'];
                }
                if(is_string($routeMap)) {
                    $resolved = $routeMap;
                    break;
                }
            }
        }
        
        return $resolved;
    }
    
    //setters/getters
    public function getRouteResourceMap() {
        return $this->routeResourceMap;
    }

    public function setRouteResourceMap($routeResourceMap) {
        $this->routeResourceMap = $routeResourceMap;
    }

    public function getDefaultRouteResource() {
        return $this->defaultRouteResource;
    }

    public function setDefaultRouteResource($defaultRouteResource) {
        $this->defaultRouteResource = $defaultRouteResource;
    }

    public function getAclService() {
        return $this->aclService;
    }

    public function setAclService($aclService) {
        $this->aclService = $aclService;
    }

}