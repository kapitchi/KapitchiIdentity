<?php

namespace KapitchiIdentity\Service;

use ZfcBase\Service\ModelServiceAbstract,
    ZfcBase\Mapper\Transactional,
    KapitchiIdentity\Model\Identity,
    KapitchiIdentity\Model\IdentityRole as IdentityRoleModel,
    KapitchiIdentity\Model\Registration as RegistrationModel;

class Registration extends ModelServiceAbstract {

    protected $identityMapper;
    protected $identityRoleMapper;
    
    public function register(array $data) {
        $model = $this->createModelFromArray($data);
        $mapper = $this->getMapper();
        //TODO DI
        $identity = new Identity();
        $identityRole = new IdentityRoleModel();
        $identityRole->setRoleId('user');
        
        $params = array(
            'data' => $data,
            'model' => $model,
            'identityModel' => $identity,
            'identityRoleModel' => $identityRole,
        );
        
        try {
            if($mapper instanceof Transactional) {
                $mapper->beginTransaction();
            }
            
            $params = $this->triggerParamsMergeEvent('register.pre', $params);

            $this->getIdentityMapper()->persist($identity);
            
            $identityRole->setIdentityId($identity->getId());
            $this->getIdentityRoleMapper()->persist($identityRole);
            
            $model->setIdentityId($identity->getId());
            $mapper->persist($model);

            $params = $this->triggerParamsMergeEvent('register.post', $params);
            
            if($mapper instanceof Transactional) {
                $mapper->commit();
            }
        }
        catch(\Exception $e) {
            if($mapper instanceof Transactional) {
                $mapper->rollback();
            }
            throw $e;
        }
        
        return $params;
    }
    
    protected function attachDefaultListeners() {
        parent::attachDefaultListeners();
        
        $events = $this->events();
        $events->attach('register.pre', function($e) {
            $now = new \DateTime();
            $e->getParam('model')->setCreated($now);
            $e->getParam('identityModel')->setCreated($now);
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


}