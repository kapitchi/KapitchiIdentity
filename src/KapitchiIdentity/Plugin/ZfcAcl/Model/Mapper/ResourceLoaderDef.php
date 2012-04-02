<?php

namespace KapitchiIdentity\Plugin\ZfcAcl\Model\Mapper;

interface ResourceLoaderDef {
    public function findByResourceClass($resourceId);
}