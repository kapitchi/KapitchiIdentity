<?php
return array(
    'KapitchiIdentity' => array(
        'options' => array(
            'identity' => array(
                'view' => array(
                    'item_count_per_page' => 10,
                )
            ),
            'auth' => array(
                'strategies' => array(
                    'KapitchiIdentity\AuthStrategy\Credential' => true,
                )
            ),
            'plugins' => array(
                'ZfcAcl' => true
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
            //auth strategies
            'KapitchiIdentity\AuthStrategy\Credential' => array(
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
            //plugins
            'KapitchiIdentity\Plugin\ZfcAcl' => array(
                'parameters' => array(
                    'aclService' => 'ZfcAcl\Service\Acl',
                    'authService' => 'KapitchiIdentity\Service\Auth',
                ),
            ),
            
            //ACL plugin
            'ZfcAcl\Model\Mapper\AclLoaderConfig' => array(
                'parameters' => array(
                    'config' => array(
                        'resources' => array(
                            'KapitchiIdentity' => array(
                                //models
                                'KapitchiIdentity/Model/Identity' => null,
                                //routes
                                'KapitchiIdentity/Route' => null,
                                'KapitchiIdentity/Route/Identity' => null,
                                'KapitchiIdentity/Route/Auth/Login' => null,
                                'KapitchiIdentity/Route/Auth/Logout' => null,
                            ),
                        ),
                        'rules' => array(
                            'allow' => array(
                                //models
                                //TODO XXX mz: finish this - for testing only now!
                                'KapitchiIdentity/allow/model/identity' => array('user', 'KapitchiIdentity/Model/Identity'),
                                //routes
                                'KapitchiIdentity/allow/route/identity' => array('user', 'KapitchiIdentity/Route/Identity'),
                                'KapitchiIdentity/allow/route/auth/logout' => array('auth', 'KapitchiIdentity/Route/Auth/Logout'),
                                'KapitchiIdentity/allow/route/auth/login' => array('guest', 'KapitchiIdentity/Route/Auth/Login'),
                                'KapitchiIdentity/allow/route/auth/login' => array('guest', 'KapitchiIdentity/Route/Auth/Login'),
                             ),
                            'deny' => array(
                                'KapitchiIdentity/deny/route/default_route' => array('guest', 'KapitchiIdentity/Route'),
                             ),
                        ),
                    ),
                ),
            ),
            'ZfcAcl\Model\Mapper\RouteResourceMapConfig' => array(
                'parameters' => array(
                    'default' => 'Route',
                    'config' => array(
                        'child_map' => array(
                            'KapitchiIdentity' => array(
                                'default' => 'KapitchiIdentity/Route',
                                'child_map' => array(
                                    'Identity' => 'KapitchiIdentity/Route/Identity',
                                    'Auth' => array(
                                        'child_map' => array(
                                            'Login' => 'KapitchiIdentity/Route/Auth/Login',
                                            'Logout' => 'KapitchiIdentity/Route/Auth/Logout',
                                         )
                                     )
                                 )
                            )
                        )
                    ),
                ),
            ),
            'ZfcAcl\Model\Mapper\EventGuardDefMapConfig' => array(
                'parameters' => array(
                    'config' => array(
                        'KapitchiIdentity/Model/Identity.get' => array(
                            'eventId' => 'KapitchiIdentity\Service\Identity',
                            'event' => 'get.load',
                            'resource' => 'KapitchiIdentity/Model/Identity',
                            'privilege' => 'get',
                        ),
                        'KapitchiIdentity/Model/Identity.persist' => array(
                            'eventId' => 'KapitchiIdentity\Service\Identity',
                            'event' => 'persist.pre',
                            'resource' => 'KapitchiIdentity/Model/Identity',
                            'privilege' => 'persist',
                        ),
                        'KapitchiIdentity/Model/Identity.remove' => array(
                            'eventId' => 'KapitchiIdentity\Service\Identity',
                            'event' => 'remove.pre',
                            'resource' => 'KapitchiIdentity/Model/Identity',
                            'privilege' => 'remove',
                        ),
                    )
                )
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
                    ),
                ),
            ),
            
        ),
    ),
);
