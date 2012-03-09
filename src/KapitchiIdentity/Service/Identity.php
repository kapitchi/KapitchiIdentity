<?php

namespace KapitchiIdentity\Service;

use Zend\Form\Form,
        KapitchiBase\Service\ServiceAbstract;

class Identity extends ServiceAbstract {
    protected $mapper;
    
    /**
     * @param array $data
     * @return type 
     */
    public function persist(array $data) {
        $mapper = $this->getMapper();
        $mapper->beginTransaction();
        
        //TODO how to ACL protect this point???
        //  resoure ................. permission.... roles
        //'KapitchiIdentity\Service', 'persist.pre', array('admin'), 
        $params = $this->triggerParamsMergeEvent('persist.pre', array('data' => $data));
        
        $model = $this->createModelFromArray($params['data']);
        $ret = $mapper->persist($model);
        $params['identity'] = $model;
        
        $params = $this->triggerParamsMergeEvent('persist.post', $params);
        
        $mapper->commit();
        
        return $params;
    }
    
    public function update(array $params) {
        //TODO
        throw new \Exception("N/I");
    }
    
    public function delete(array $params) {
        //TODO
        throw new \Exception("N/I");
    }
    
    public function setMapper($mapper) {
        $this->mapper = $mapper;
    }
    
    public function getMapper() {
        return $this->mapper;
    }
    
    protected function createModelFromArray(array $data) {
        //TODO matusz: use locator for this
        $model = \KapitchiIdentity\Model\Identity::fromArray($data);
        return $model;
    }
}