<?php

namespace KapitchiIdentity\Model\Mapper;

use KapitchiIdentity\Model\Identity as Model;

interface Identity {
    public function findById($id);
    public function persist(Model $model);
    public function remove(Model $model);
    public function getPaginatorAdapter(array $params);
}