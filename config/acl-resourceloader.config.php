<?php

return array(
    //resource class name
    'KapitchiIdentity\Model\Identity' => array(
        'parent_resource' => 'KapitchiIdentity\Model\Identity',
        'allow_rules' => array(
            //owner can can do everything!
            'ownerId' => null,
            //currently logged in user can only 'get' its own identity
            'id' => array('get'),
        )
    ),
);