<?php
return array(
    'KapitchiIdentity' => array(
        'options' => array(
            'identity' => array(
                'view' => array(
                    'item_count_per_page' => 10,
                )
            )
        ),
    ),
    'di' => array(
        'definition' => array(
            'class' => array(
            )
        ),
        'instance' => array(
            'Zend\Authentication\Adapter\Http' => array(
                'parameters' => array(
                    'config' => array(
                        'accept_schemes' => 'basic',
                        'realm' => 'My Site!',
                     ),
                ),
            ),
            //controllers
            'KapitchiIdentity\Controller\AuthController' => array(
                'parameters' => array(
                    'authService' => 'KapitchiIdentity\Service\Auth',
                    'loginForm' => 'KapitchiIdentity\Form\Login',
                    'loginViewModel' => 'KapitchiIdentity\View\Model\AuthLogin',
                ),
            ),
            'KapitchiIdentity\Controller\IdentityController' => array(
                'parameters' => array(
                    'identityService' => 'KapitchiIdentity\Service\Identity',
                    'identityForm' => 'KapitchiIdentity\Form\Identity',
                ),
            ),
            
            //SERVICES
            'KapitchiIdentity\Service\Identity' => array(
                'parameters' => array(
                    'modelPrototype' => 'KapitchiIdentity\Model\Identity',
                    'mapper' => 'KapitchiIdentity\Model\Mapper\IdentityZendDb',
                ),
            ),
            'KapitchiIdentity\Service\Auth\Credential' => array(
                'parameters' => array(
                    'credentialMapper' => 'KapitchiIdentity\Model\Mapper\AuthCredentialZendDb',
                    'credentialLoginForm' => 'KapitchiIdentity\Form\AuthCredentialLogin',
                ),
            ),
            //mappers
            'KapitchiIdentity\Model\Mapper\IdentityZendDb' => array(
                'parameters' => array(
                    'adapter' => 'Zend\Db\Adapter\Adapter',
                ),
            ),
            //ACL
            'KapitchiAcl\Model\Mapper\AclLoaderConfig' => array(
                'parameters' => array(
                    'config' => array(
                        'resources' => array(
                            'KapitchiIdentity' => array(
                                'KapitchiIdentity/Route' => null,
                                'KapitchiIdentity/Route/Identity' => null,
                                'KapitchiIdentity/Route/Auth/Login' => null,
                                'KapitchiIdentity/Route/Auth/Logout' => null,
                            ),
                        ),
                        'rules' => array(
                            'allow' => array(
                                'KapitchiIdentity/allow/identity/default' => array('user', 'KapitchiIdentity/Route/Identity'),
                                'KapitchiIdentity/allow/auth/logout' => array('auth', 'KapitchiIdentity/Route/Auth/Logout'),
                                'KapitchiIdentity/allow/auth/login' => array('guest', 'KapitchiIdentity/Route/Auth/Login'),
                             ),
                            'deny' => array(
                                'KapitchiIdentity/deny/default_route' => array('guest', 'KapitchiIdentity/Route'),
                             ),
                        ),
                    ),
                ),
            ),
            'KapitchiAcl\Model\Mapper\RouteResourceMapConfig' => array(
                'parameters' => array(
                    'config' => array(
                        'child_map' => array(
                            'KapitchiIdentity' => array(
                                'default' => 'KapitchiIdentity/Route',
                                'child_map' => array(
                                    'identity' => 'KapitchiIdentity/Route/Identity',
                                    'auth' => array(
                                        'child_map' => array(
                                            'login' => 'KapitchiIdentity/Route/Auth/Login',
                                            'logout' => 'KapitchiIdentity/Route/Auth/Logout',
                                         )
                                     )
                                 )
                            )
                        )
                    ),
                ),
            ),
            //View models
            'KapitchiIdentity\View\Model\AuthLogin' => array(
                'parameters' => array(
                    'template' => 'auth/login',
                    'authService' => 'KapitchiIdentity\Service\Auth',
                ),
            ),
            
            //VIEW
            'Zend\View\Resolver\TemplatePathStack' => array(
                'parameters'  => array(
                    'paths' => array(
                        'kapitchiidentity' => __DIR__ . '/../view',
                    ),
                ),
            ),
            'Zend\View\HelperLoader' => array(
                'parameters' => array(
                    'map' => array(
                        'identity' => 'KapitchiIdentity\View\Helper\Identity',
                    ),
                ),
            ),
            
            //ROUTER
            'Zend\Mvc\Router\RouteStack' => array(
                'parameters' => array(
                    'routes' => array(
                        'KapitchiIdentity' => array(
                            'type' => 'Literal',
                            'options' => array(
                                'route'    => '/KapitchiIdentity',
                            ),
                            'may_terminate' => false,
                            'child_routes' => array(
                                'identity' => array(
                                    'type' => 'Literal',
                                    'options' => array(
                                        'route'    => '/identity',
                                    ),
                                    'may_terminate' => false,
                                    'child_routes' => array(
                                        'me' => array(
                                            'type' => 'Literal',
                                            'options' => array(
                                                'route' => '/me',
                                                'defaults' => array(
                                                    'controller' => 'KapitchiIdentity\Controller\IdentityController',
                                                    'action'     => 'me',
                                                ),
                                            ),
                                        ),
                                        'login' => array(
                                            'type' => 'Literal',
                                            'options' => array(
                                                'route' => '/login',
                                                'defaults' => array(
                                                    'controller' => 'KapitchiIdentity\Controller\AuthController',
                                                    'action'     => 'login',
                                                ),
                                            ),
                                        ),
                                        'logout' => array(
                                            'type' => 'Literal',
                                            'options' => array(
                                                'route' => '/logout',
                                                'defaults' => array(
                                                    'controller' => 'KapitchiIdentity\Controller\AuthController',
                                                    'action'     => 'logout',
                                                ),
                                            ),
                                        ),
                                        'register' => array(
                                            'type' => 'Literal',
                                            'options' => array(
                                                'route' => '/register',
                                                'defaults' => array(
                                                    'controller' => 'KapitchiIdentity\Controller\IdentityController',
                                                    'action'     => 'register',
                                                ),
                                            ),
                                        ),
                                        'create' => array(
                                            'type' => 'Literal',
                                            'options' => array(
                                                'route' => '/create',
                                                'defaults' => array(
                                                    'controller' => 'KapitchiIdentity\Controller\IdentityController',
                                                    'action'     => 'create',
                                                ),
                                            ),
                                        ),
                                        'update' => array(
                                            'type' => 'Segment',
                                            'options' => array(
                                                'route' => '/update/:id',
                                                'defaults' => array(
                                                    'controller' => 'KapitchiIdentity\Controller\IdentityController',
                                                    'action'     => 'update',
                                                ),
                                            ),
                                        ),
                                        'index' => array(
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
                                                    'controller' => 'KapitchiIdentity\Controller\AuthController',
                                                    'action'     => 'login',
                                                ),
                                            ),
                                        ),
                                        'logout' => array(
                                            'type' => 'Literal',
                                            'options' => array(
                                                'route' => '/logout',
                                                'defaults' => array(
                                                    'controller' => 'KapitchiIdentity\Controller\AuthController',
                                                    'action'     => 'logout',
                                                ),
                                            ),
                                        ),
                                        'register' => array(
                                            'type' => 'Literal',
                                            'options' => array(
                                                'route' => '/register',
                                                'defaults' => array(
                                                    'controller' => 'KapitchiIdentity\Controller\IdentityController',
                                                    'action'     => 'register',
                                                ),
                                            ),
                                        ),
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
            
        ),
    ),
);
