<?php

return array(
    'kapitchi-identity' => array(
        'type' => 'Literal',
        'options' => array(
            'route'    => '/identity',
            'defaults' => array(
                '__NAMESPACE__' => 'KapitchiIdentity\Controller',
            ),
        ),
        'may_terminate' => false,
        'child_routes' => array(
            'profile' => array(
                'type' => 'Literal',
                'options' => array(
                    'route'    => '/profile',
                ),
                'may_terminate' => false,
                'child_routes' => array(
                    'me' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/me',
                            'defaults' => array(
                                'controller' => 'Profile',
                                'action'     => 'me',
                            ),
                        ),
                    ),
                ),
            ),
            'identity' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/identity[/:action[/:id]]',
                    'constraints' => array(
                        'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Identity',
                    ),
                ),
            ),
            'auth' => array(
                'type' => 'Literal',
                'options' => array(
                    'route'    => '/auth',
                ),
                'may_terminate' => false,
                'child_routes' => array(
                    'login' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/login',
                            'defaults' => array(
                                'controller' => 'Auth',
                                'action'     => 'login',
                            ),
                        ),
                    ),
                    'logout' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/logout',
                            'defaults' => array(
                                'controller' => 'Auth',
                                'action'     => 'logout',
                            ),
                        ),
                    ),
                ),
            ),
            'registration' => array(
                'type' => 'Literal',
                'options' => array(
                    'route'    => '/registration',
                ),
                'may_terminate' => false,
                'child_routes' => array(
                    'Register' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/register',
                            'defaults' => array(
                                'controller' => 'Registration',
                                'action'     => 'register',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
);