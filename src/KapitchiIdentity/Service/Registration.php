<?php

namespace KapitchiIdentity\Service;

use ZfcBase\Service\ModelServiceAbstract,
    ZfcBase\Mapper\Transactional,
    KapitchiIdentity\Model\Identity,
    KapitchiIdentity\Model\IdentityRole as IdentityRoleModel,
    KapitchiIdentity\Model\Registration as RegistrationModel;

class Registration extends ModelServiceAbstract {

    protected $aclContextService;
    protected $identityMapper;
    protected $identityRoleMapper;
    protected $defaultRoleId = 'user';
    
    public function register(array $data) {
        $params = array(
            'data' => $data,
        );
        
        $params = $this->triggerParamsMergeEvent('register.pre', $params);
        $this->persist($data);
        
        //need to be moved to ZfcAclPlugin!!!!!
        //run persist in selfregistrator role context
        /*$aclContext = $this->getAclContextService();
        $params = $aclContext->runAs('selfregistrator', array($this, 'persist'), array($data));*/

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
    
    public function getAclContextService() {
        return $this->aclContextService;
    }

    public function setAclContextService($aclContextService) {
        $this->aclContextService = $aclContextService;
    }

}