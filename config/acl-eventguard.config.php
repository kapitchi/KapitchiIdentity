<?php

$modelServices = array(
    'KapitchiIdentity\Model\Identity' => 'KapitchiIdentity\Service\Identity',
    //'KapitchiIdentity\Model\Registration' => 'KapitchiIdentity\Service\Registration',
);

$ret = array();
foreach($modelServices as $resource => $service) {
    $ret["$resource.get"] = array(
        'class' => 'KapitchiBase\Acl\ModelEventGuardDef',
        'eventId' => $service,
        'event' => 'get.post',
        'resource' => $resource,
        'privilege' => 'get',
    );
    $ret["$resource.persist"] = array(
        'class' => 'KapitchiBase\Acl\ModelEventGuardDef',
        'eventId' => $service,
        'event' => 'persist.pre',
        'resource' => $resource,
        'privilege' => 'persist',
    );
    $ret["$resource.remove"] = array(
        'class' => 'KapitchiBase\Acl\ModelEventGuardDef',
        'eventId' => $service,
        'event' => 'remove.pre',
        'resource' => $resource,
        'privilege' => 'remove',
    );
}

return $ret;