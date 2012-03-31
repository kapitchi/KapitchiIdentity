<?php

return array(
    'KapitchiIdentity' => array(
        'type' => 'Literal',
        'options' => array(
            'route'    => '/KapitchiIdentity',
        ),
        'may_terminate' => false,
        'child_routes' => array(
            'Identity' => array(
                'type' => 'Literal',
                'options' => array(
                    'route'    => '/identity',
                ),
                'may_terminate' => false,
                'child_routes' => array(
                    'Me' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/me',
                            'defaults' => array(
                                'controller' => 'KapitchiIdentity\Controller\IdentityController',
                                'action'     => 'me',
                            ),
                        ),
                    ),
                    'Login' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/login',
                            'defaults' => array(
                                'controller' => 'KapitchiIdentity\Controller\AuthController',
                                'action'     => 'login',
                            ),
                        ),
                    ),
                    'Logout' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/logout',
                            'defaults' => array(
                                'controller' => 'KapitchiIdentity\Controller\AuthController',
                                'action'     => 'logout',
                            ),
                        ),
                    ),
                    'Create' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/create',
                            'defaults' => array(
                                'controller' => 'KapitchiIdentity\Controller\IdentityController',
                                'action'     => 'create',
                            ),
                        ),
                    ),
                    'Update' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/update/:id',
                            'defaults' => array(
                                'controller' => 'KapitchiIdentity\Controller\IdentityController',
                                'action'     => 'update',
                            ),
                        ),
                    ),
                    'Index' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/index[/page/:page]',
                            'defaults' => array(
                                'controller' => 'KapitchiIdentity\Controller\IdentityController',
                                'action'     => 'index',
                            ),
                        ),
                    ),

                ),
            ),
            'Auth' => array(
                'type' => 'Literal',
                'options' => array(
                    'route'    => '/auth',
                ),
                'may_terminate' => false,
                'child_routes' => array(
                    'Login' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/login',
                            'defaults' => array(
                                'controller' => 'KapitchiIdentity\Controller\AuthController',
                                'action'     => 'login',
                            ),
                        ),
                    ),
                    'Logout' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/logout',
                            'defaults' => array(
                                'controller' => 'KapitchiIdentity\Controller\AuthController',
                                'action'     => 'logout',
                            ),
                        ),
                    ),
                    'Register' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/register',
                            'defaults' => array(
                                'controller' => 'KapitchiIdentity\Controller\AuthController',
                                'action'     => 'register',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
);