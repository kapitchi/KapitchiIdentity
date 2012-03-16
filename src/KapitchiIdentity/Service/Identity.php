<?php

namespace KapitchiIdentity\Service;

use Zend\Form\Form,
        Zend\Paginator\Paginator,
        KapitchiBase\Service\ServiceAbstract,
        KapitchiIdentity\Model\Identity as IdentityModel,
    InvalidArgumentException as NoIdentityFoundException;

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
    
    public function get(array $filter, $exts = array()) {
//        if(!is_array($filter)) {
//            $filter = array('id' => $filter);
//        }
        
        $result = $this->events()->trigger('get.load', $this, $filter, function($ret) {
            return $ret instanceof IdentityModel;
        });
        $model = $result->last();
        if(!$model instanceof IdentityModel) {
            throw new NoIdentityFoundException("No identity found #$id");
        }
        
        if($exts === true) {
            $this->triggerEvent('get.exts', array(
                'identity' => $model,
            ));
        } else {
            foreach($exts as $ext) {
                $this->triggerEvent('get.ext.' . $ext, array(
                    'identity' => $model,
                ));
            }
        }
        
        $this->triggerEvent('get.post', array(
            'identity' => $model,
        ));
        
        return $model;
    }
    
    public function getPaginator($params = array()) {
        $adapter = $this->getMapper()->getPaginatorAdapter($params);
        $paginator = new Paginator($adapter);
        return $paginator;
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
    
    //listeners
    protected function attachDefaultListeners() {
        $events = $this->events();
        $mapper = $this->getMapper();
        
        //load by id
        $events->attach('get.load', function($e) use ($mapper){
            $id = $e->getParam('id');
            if(!$id) {
                return;
            }
            
            return $mapper->findById($id);
        });
        
    }
}