<?php

namespace KapitchiIdentity\Service;

use ZfcBase\Service\ModelServiceAbstract,
    ZfcBase\Mapper\Transactional,
    KapitchiIdentity\Model\Identity as IdentityModel,
    KapitchiIdentity\Model\IdentityRole as IdentityRoleModel,
    KapitchiIdentity\Model\Registration as RegistrationModel;

class Registration extends ModelServiceAbstract {

    protected $identityMapper;
    protected $identityRoleMapper;
    
    public function register(array $data) {
        $params = array(
            'data' => $data,
        );
        
        $params = $this->triggerParamsMergeEvent('register.pre', $params);
        
        $params = $this->persist($data);

        $params = $this->triggerParamsMergeEvent('register.post', $params);
            
        return $params;
    }
    
    protected function attachDefaultListeners() {
        parent::attachDefaultListeners();
        
        $events = $this->events();
        $events->attach('persist.pre', function($e) {
            $now = new \DateTime();
            $e->getParam('model')->setCreated($now);
        });
        
        $mapper = $this->getMapper();
        $events->attach('get.load', function($e) use ($mapper){
            $filter = $e->getParam('identityId');
            if(!$filter) {
                return;
            }
            return $mapper->findByIdentityId($filter);
        });
    }
    
    //getters/setters
    public function getIdentityMapper() {
        return $this->identityMapper;
    }

    public function setIdentityMapper($identityMapper) {
        $this->identityMapper = $identityMapper;
    }
    
    public function getIdentityRoleMapper() {
        return $this->identityRoleMapper;
    }

    public function setIdentityRoleMapper($identityRoleMapper) {
        $this->identityRoleMapper = $identityRoleMapper;
    }

    public function getDefaultRoleId() {
        return $this->defaultRoleId;
    }

    public function setDefaultRoleId($defaultRoleId) {
        $this->defaultRoleId = $defaultRoleId;
    }
    
}