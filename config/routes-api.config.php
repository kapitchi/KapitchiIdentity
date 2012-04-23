<?php

return array(
    'type' => 'Literal',
    'options' => array(
        'route'    => '/api',
    ),
    'may_terminate' => false,
    'child_routes' => array(
        'Identity' => array(
            'type' => 'Segment',
            'options' => array(
                'route'    => '/identity[/:id]',
                'defaults' => array(
                    'controller' => 'KapitchiIdentity\Api\Controller\IdentityController'
                ),
            ),
        ),
        'IdentityRole' => array(
            'type' => 'Segment',
            'options' => array(
                'route'    => '/identityrole/[/:id]',
                'defaults' => array(
                    'controller' => 'KapitchiIdentity\Api\Controller\IdentityRoleController'
                ),
            ),
        ),
        'AuthCredential' => array(
            'type' => 'Segment',
            'options' => array(
                'route'    => '/authcredential/[/:id]',
                'defaults' => array(
                    'controller' => 'KapitchiIdentity\Api\Controller\AuthCredentialController'
                ),
            ),
        ),
    ),
);