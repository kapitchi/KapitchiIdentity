<?php

return array(
    'KapitchiIdentity/Model/Identity.get' => array(
        'eventId' => 'KapitchiIdentity\Service\Identity',
        'event' => 'get.load',
        'resource' => 'KapitchiIdentity/Service/Identity',
        'privilege' => 'get',
    ),
    'KapitchiIdentity/Model/Identity.persist' => array(
        'eventId' => 'KapitchiIdentity\Service\Identity',
        'event' => 'persist.pre',
        'resource' => 'KapitchiIdentity/Service/Identity',
        'privilege' => 'persist',
    ),
    'KapitchiIdentity/Model/Identity.remove' => array(
        'eventId' => 'KapitchiIdentity\Service\Identity',
        'event' => 'remove.pre',
        'resource' => 'KapitchiIdentity/Service/Identity',
        'privilege' => 'remove',
    ),
);