<?php

namespace KapitchiIdentity\Model\Mapper;

use KapitchiIdentity\Model\Mapper\IdentityInterface,
    ZfcBase\Mapper\DbAdapterMapper,
    ZfcBase\Model\ModelAbstract,
    KapitchiIdentity\Model\Identity;
    //Zend\Paginator\Adapter as PaginatorAdapter;
    //Zend\Paginator\AdapterAggregate;

class IdentityDbAdapter extends DbAdapterMapper implements IdentityInterface {
    protected $tableName = 'identity';
    private $identityTable;
    protected $modelPrototype;
    
    public function findByPriKey($id) {
        $identityTable = $this->getIdentityTable();
        $result = $identityTable->select(array(
            'id' => $id
        ));
        $row = $result->current();
        if(!$row) {
            return null;
        }
        
        $model = $this->getModelPrototype();
        $model->exchangeArray($row->getArrayCopy());
        return $model;
    }
    
    public function persist(ModelAbstract $model) {
        $identityTable = $this->getIdentityTable();
        $data = $this->toScalarValueArray($model);
        if($model->getId()) {
            unset($data['id']);
            $ret = $identityTable->update($data, array('id' => $model->getId()));
        }
        else {
            $ret = $identityTable->insert($data);
            $model->setId((int)$identityTable->getLastInsertId());
        }
        
        return $ret;
    }
    
    public function remove(ModelAbstract $model) {
        $identityTable = $this->getIdentityTable();
        $ret = $identityTable->delete(array('id' => $model->getId()));
        
        return $ret;
    }
    
    /**
     * TODO finish this properly!
     * @param array $params
     * @return \Zend\Paginator\Adapter\Iterator 
     */
    public function getPaginatorAdapter(array $params) {
        $this->getIdentityTable()->setSelectResultPrototype(new \Zend\Db\ResultSet\ResultSet($this->getModelPrototype()));
        $iterator = $this->getIdentityTable()->select();
        $array = array();
        foreach($iterator as $item) {
            $array[] = $item;
        }
        return new \Zend\Paginator\Adapter\ArrayAdapter($array);
    }
    
    protected function getIdentityTable() {
        if($this->identityTable === null) {
            $this->identityTable = $this->getTableGateway($this->tableName, true);
        }
        return $this->identityTable;
    }
    
    public function getModelPrototype() {
        return clone $this->modelPrototype;
    }

    public function setModelPrototype(ModelAbstract $modelPrototype) {
        $this->modelPrototype = $modelPrototype;
    }


}