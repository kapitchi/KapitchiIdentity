<?php

namespace KapitchiIdentity\Model\Mapper;

use KapitchiIdentity\Model\Mapper\Registration as RegistrationMapper,
    ZfcBase\Mapper\DbAdapterMapper,
    ZfcBase\Model\ModelAbstract,
    KapitchiIdentity\Model\Registration;

class RegistrationDbAdapter extends DbAdapterMapper implements RegistrationMapper {
    protected $tableName = 'identity_registration';
    
    public function findByPriKey($id) {
        $table = $this->getTableGateway($this->tableName);
        $result = $table->select(array(
            'id' => $id
        ));
        $row = $result->current();
        if(!$row) {
            return null;
        }
        
        return $this->createModelFromArray($row->getArrayCopy());
    }
    
    public function persist(ModelAbstract $model) {
        $table = $this->getTableGateway($this->tableName, true);
        $data = $model->toArray();
        if(!empty($data['data']) && !is_scalar($data['data'])) {
            $data['data'] = serialize($data['data']);
        }
        $data = $this->toScalarValueArray($data);
        if($model->getId()) {
            unset($data['id']);
            $ret = $table->update($data, array('id' => $model->getId()));
        }
        else {
            $ret = $table->insert($data);
            $model->setId((int)$table->getLastInsertId());
        }
        
        return $ret;
    }
    
    public function remove(ModelAbstract $model) {
        var_dump($model);
        exit;
    }
    
    /**
     * @param array $params
     * @return \Zend\Paginator\Adapter\Iterator 
     */
    public function getPaginatorAdapter(array $params) {
        var_dump($params);
        exit;
    }
    
    protected function createModelFromArray(array $data) {
        if(!empty($data['data'])) {
            $data['data'] = unserialize($data['data']);
        }
        
        return Registration::fromArray($data);
    }
    
}