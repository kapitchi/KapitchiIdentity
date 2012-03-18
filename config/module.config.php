<?php
return array(
    'KapitchiIdentity' => array(
        'options' => array(
            'acl' => array(
                'enable_cache' => false
            )
        ),
    ),
    //XXX ACL is used 
    'acl' => array(
        'resources' => array(
            
        )
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
            //DB adapter
            'Zend\Db\Adapter\Adapter' => array(
                'parameters' => array(
                    'driver' => array(
                        'driver' => 'Pdo',
                        'username' => 'root',
                        'password' => '',
                        'dsn'   => 'mysql:dbname=creditors_drazobnik;hostname=localhost',
                    ),
                )
            ),
            
            'KapitchiIdentity\Model\Mapper\IdentityZendDb' => array(
                'parameters' => array(
                    'adapter' => 'Zend\Db\Adapter\Adapter',
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
            
            //ROUTER
            'Zend\Mvc\Router\RouteStack' => array(
                'parameters' => array(
                    'routes' => array(
                        'KapitchiIdentity' => array(
                            'type' => 'Zend\Mvc\Router\Http\Literal',
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
                                'read' => array(
                                    'type' => 'Literal',
                                    'options' => array(
                                        'route' => '/read',
                                        'defaults' => array(
                                            'controller' => 'KapitchiIdentity\Controller\IdentityController',
                                            'action'     => 'read',
                                        ),
                                    ),
                                ),
                                'create' => array(
                                    'type' => 'Literal',
                                    'options' => array(
                                        'route' => '/edit',
                                        'defaults' => array(
                                            'controller' => 'KapitchiIdentity\Controller\IdentityController',
                                            'action'     => 'edit',
                                        ),
                                    ),
                                ),
                                'index' => array(
                                    'type' => 'Literal',
                                    'options' => array(
                                        'route' => '/index',
                                        'defaults' => array(
                                            'controller' => 'KapitchiIdentity\Controller\IdentityController',
                                            'action'     => 'index',
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
