<?php
return array(
    'kapitchiidentity' => array(
        'auth_adapters' => array('kapitchi-http_auth_adapter'),
    ),
    //XXX ACL is used 
    'acl' => array(
        'resources' => array(
            
        )
    ),
    'di' => array(
        'definition' => array(
            'class' => array(
//                'Zend\Acl\Acl' => array(
//                    'addRole' => array(
//                        'block' => array('type' => 'Application\Block', 'required' => true)
//                    )
//                )
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
            
            //SERVICES
            'KapitchiIdentity\Service\Identity' => array(
                'parameters' => array(
                    'mapper' => 'KapitchiIdentity\Model\Mapper\IdentityZendDb',
                ),
            ),
            'KapitchiIdentity\Service\Auth\Credential' => array(
                'parameters' => array(
                    'credentialMapper' => 'KapitchiIdentity\Model\Mapper\AuthCredentialZendDb',
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
                        'kapitchiidentity' => array(
                            'type' => 'Zend\Mvc\Router\Http\Literal',
                            'options' => array(
                                'route'    => '/identity',
                                'defaults' => array(
                                    'controller' => 'KapitchiIdentity\Controller\AuthController',
                                    'action'     => 'index',
                                ),
                            ),
                            'may_terminate' => true,
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
                                'authenticate' => array(
                                    'type' => 'Literal',
                                    'options' => array(
                                        'route' => '/authenticate',
                                        'defaults' => array(
                                            'controller' => 'KapitchiIdentity\Controller\AuthController',
                                            'action'     => 'authenticate',
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
);
