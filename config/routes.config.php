<?php

return array(
    'identity' => array(
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
                    'route'    => '/identity/:action[/:id]',
                    'constraints' => array(
                        'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Identity',
                    ),
                ),
            ),
            'auth-session' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/auth-session',
                    'defaults' => array(
                        'controller' => 'KapitchiIdentity\Controller\AuthSession',
                    ),
                ),
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/:action[/:id]',
                            'constraints' => array(
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                        ),
                    ),
                    'switch' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/switch/:sessionId',
                            'defaults' => array(
                                'action'     => 'switch',
                            ),
                        ),
                    ),
                )
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
            'api' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/api',
                    'defaults' => array(
                        '__NAMESPACE__' => 'KapitchiIdentity\Controller\Api',
                    ),
                ),
                'may_terminate' => false,
                'child_routes' => array(
                    'auth' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/auth/:action',
                            'constraints' => array(
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Auth',
                            ),
                        ),
                    ),
                    'identity' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/identity[/:action][/:id]',
                            'constraints' => array(
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Identity',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
);