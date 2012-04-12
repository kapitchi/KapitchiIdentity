<?php

namespace KapitchiIdentityAcl\Model\Mapper;

interface ResourceLoaderDef {
    public function findByResourceClass($resourceId);
}