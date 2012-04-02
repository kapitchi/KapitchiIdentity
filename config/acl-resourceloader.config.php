<?php

return array(
    'KapitchiIdentity\Model\Identity' => array(
        'parent_role' => 'user',
        'parent_resource' => 'KapitchiIdentity\Model\Identity',
        'allow_rules' => array(
            //owner can can do everything!
            'ownerId' => null,
            'id' => array('get'),
        )
    )
);