<?php

namespace KapitchiIdentity\Model\Mapper;

use KapitchiIdentity\Model\Mapper\Identity as IdentityMapper,
    KapitchiBase\Mapper\DbAdapterMapper,
    KapitchiBase\Model\ModelAbstract,
    KapitchiIdentity\Model\Identity;
    //Zend\Paginator\Adapter as PaginatorAdapter;
    //Zend\Paginator\AdapterAggregate;

class IdentityDbAdapter extends DbAdapterMapper implements IdentityMapper {
    protected $tableName = 'identity';
    private $identityTable;
    
    public function findByPriKey($id) {
        $identityTable = $this->getIdentityTable();
        $result = $identityTable->select(array(
            'id' => $id
        ));
        $row = $result->current();
        if(!$row) {
            return null;
        }
        
        return Identity::fromArray($row->getArrayCopy());
    }
    
    public function persist(ModelAbstract $model) {
        if($model->getId()) {
            $ret = $this->update($model);
        }
        else {
            $ret = $this->insert($model);
        }
        
        return $ret;
    }
    
    public function remove(ModelAbstract $model) {
        var_dump($model);
        exit;
    }
    
    /**
     * TODO finish this properly!
     * @param array $params
     * @return \Zend\Paginator\Adapter\Iterator 
     */
    public function getPaginatorAdapter(array $params) {
        $this->getIdentityTable()->setSelectResultPrototype(new \Zend\Db\ResultSet\ResultSet(new Identity));
        $iterator = $this->getIdentityTable()->select();
        $array = array();
        foreach($iterator as $item) {
            $array[] = $item;
        }
        return new \Zend\Paginator\Adapter\ArrayAdapter($array);
    }
    
    protected function insert(ModelAbstract $model) {
        $identityTable = $this->getIdentityTable();
        
        $data = $model->toArray();
        $ret = $identityTable->insert($data);
        $model->setId((int)$identityTable->getLastInsertId());
        
        return $ret;
    }
    
    protected function update(ModelAbstract $model) {
        $identityTable = $this->getIdentityTable();
        
        $data = $model->toArray();
        unset($data['id']);
        $ret = $identityTable->update($data, array('id' => $model->getId()));
        
        return $ret;
    }
    
    protected function getIdentityTable() {
        if($this->identityTable === null) {
            $this->identityTable = $this->getTableGateway($this->tableName, true);
        }
        return $this->identityTable;
    }
}