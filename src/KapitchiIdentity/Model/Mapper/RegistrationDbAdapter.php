<?php

namespace KapitchiIdentity\Model\Mapper;

use KapitchiIdentity\Model\Mapper\Registration as RegistrationMapper,
    ZfcBase\Mapper\DbAdapterMapper,
    ZfcBase\Model\ModelAbstract,
    KapitchiIdentity\Model\Registration;

class RegistrationDbAdapter extends DbAdapterMapper implements RegistrationMapper {
    protected $tableName = 'identity';
    
    public function findByPriKey($id) {
        $table = $this->getTableGateway($this->tableName);
        $result = $table->select(array(
            'id' => $id
        ));
        $row = $result->current();
        if(!$row) {
            return null;
        }
        
        return Registration::fromArray($row->getArrayCopy());
    }
    
    public function persist(ModelAbstract $model) {
        $table = $this->getTableGateway($this->tableName, true);

        $data = $this->toScalarValueArray(array(
            'registrationRequestIp' => $model->getRequestIp(),
            'registered' => $model->getCreated(),
        ));
        $ret = $table->update($data, array('id' => $model->getIdentityId()));
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
    
}