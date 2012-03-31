<?php
return array(
    'KapitchiIdentity' => array(
        'options' => array(
            'identity' => array(
                'view' => array(
                    'item_count_per_page' => 10,//identity index page - number of identies to render per page
                )
            ),
            'auth' => array(
                //you can register your new strategies by adding it into this option array
                'strategies' => array(
                    'KapitchiIdentity\AuthStrategy\Credential' => true,//username/password strategy
                    'KapitchiIdentity\AuthStrategy\Http' => false,//http strategy - needs to be finished
                )
            ),
            //these plugins can be disabled by setting them to false - e.g. 'IdentityAuthCredentialModel' => false
            'plugins' => array(
                //used to add credential (username/password) form when editing/creating identity
                'IdentityAuthCredentialModel' => array(
                    'class' => 'KapitchiIdentity\Plugin\IdentityAuthCredentialModel',
                ),
                //adds role management for identities - identity form
                'IdentityRoleModel' => array(
                    'class' => 'KapitchiIdentity\Plugin\IdentityRoleModel',
                ),
                'RegistrationAuthCredential' => array(
                    'class' => 'KapitchiIdentity\Plugin\RegistrationAuthCredential',
                ),
                //ZfcAcl module does not manage roles itself - it relies on other modules to provide it - this plugin does exactly this
                'ZfcAcl' => array(
                    'class' => 'KapitchiIdentity\Plugin\ZfcAcl',
                )
            )
            
            //SEE BELOW - "DI options" for more options
            
        ),
    ),
    'di' => array(
        'definition' => array(
            'class' => array(
            )
        ),
        'instance' => array(
            //DI options
            'auth_credential_password_hash' => array(
                'parameters' => array(
                    'sharedSalt' => 'TODO-CHANGE ME!',//this is "shared salt" used to prefix all passwords and then encrypted to add additional protection
                    'algorithm' => 'md5',//algorithm to be used to encrypt passwords - 'md5', 'blowfish', 'sha256', 'sha512'
                    'cost' => 0,//used to set rounds param for e.g. sha256, ... see http://php.net/manual/en/function.crypt.php 
                )
            ),
            
            //END - DI options
            
            'alias' => array(
                'auth_credential_password_hash' => 'KapitchiBase\Crypt\Hash'
            ),
            
            //XXX
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
            'KapitchiIdentity\Controller\RegistrationController' => array(
                'parameters' => array(
                    'registrationService' => 'KapitchiIdentity\Service\Registration',
                    'registrationForm' => 'KapitchiIdentity\Form\Registration',
                ),
            ),
            'KapitchiIdentity\Controller\ProfileController' => array(
                'parameters' => array(
                    'identityService' => 'KapitchiIdentity\Service\Identity',
                ),
            ),
            
            //SERVICES
            'KapitchiIdentity\Service\Identity' => array(
                'parameters' => array(
                    'modelPrototype' => 'KapitchiIdentity\Model\Identity',
                    'mapper' => 'KapitchiIdentity\Model\Mapper\IdentityDbAdapter',
                ),
            ),
            'KapitchiIdentity\Service\IdentityRole' => array(
                'parameters' => array(
                    'authService' => 'KapitchiIdentity\Service\Auth',
                    'mapper' => 'KapitchiIdentity\Model\Mapper\IdentityRoleDbAdapter',
                    'modelPrototype' => 'KapitchiIdentity\Model\IdentityRole',
                ),
            ),
            'KapitchiIdentity\Service\AuthCredential' => array(
                'parameters' => array(
                    'mapper' => 'KapitchiIdentity\Model\Mapper\AuthCredentialDbAdapter',
                    'modelPrototype' => 'KapitchiIdentity\Model\AuthCredential',
                    'passwordHash' => 'auth_credential_password_hash',
                ),
            ),
            'KapitchiIdentity\Service\Registration' => array(
                'parameters' => array(
                    'mapper' => 'KapitchiIdentity\Model\Mapper\RegistrationDbAdapter',
                    'identityMapper' => 'KapitchiIdentity\Model\Mapper\IdentityDbAdapter',
                    'identityRoleMapper' => 'KapitchiIdentity\Model\Mapper\IdentityRoleDbAdapter',
                    'modelPrototype' => 'KapitchiIdentity\Model\Registration',
                )
            ),
            //auth strategies
            'KapitchiIdentity\AuthStrategy\Credential' => array(
                'parameters' => array(
                    'credentialMapper' => 'KapitchiIdentity\Model\Mapper\AuthCredentialDbAdapter',
                    'passwordHash' => 'auth_credential_password_hash',
                    'credentialLoginForm' => 'KapitchiIdentity\Form\AuthCredentialLogin',
                ),
            ),
            //mappers
            'KapitchiIdentity\Model\Mapper\IdentityDbAdapter' => array(
                'parameters' => array(
                    'adapter' => 'Zend\Db\Adapter\Adapter',
                ),
            ),
            'KapitchiIdentity\Model\Mapper\IdentityRoleMapper' => array(
                'parameters' => array(
                    'adapter' => 'Zend\Db\Adapter\Adapter',
                ),
            ),
            //plugins
            'KapitchiIdentity\Plugin\ZfcAcl' => array(
                'parameters' => array(
                    //plugins should be using locators!!!
                    'aclService' => 'ZfcAcl\Service\Acl',
                    'identityRoleService' => 'KapitchiIdentity\Service\IdentityRole',
                ),
            ),
            'KapitchiIdentity\Plugin\IdentityRole' => array(
                'parameters' => array(
                    //'extName' => 'KapitchiIdentity_IdentityRole',
                    //'modelService' => 'KapitchiIdentity\Service\Identity',
                    //'modelFormClass' => 'KapitchiIdentity\Form\Identity',
                ),
            ),
            
            //ACL plugin
            'ZfcAcl\Model\Mapper\AclLoaderConfig' => array(
                'parameters' => array(
                    'config' => require 'acl.config.php'
                ),
            ),
            'ZfcAcl\Model\Mapper\RouteResourceMapConfig' => array(
                'parameters' => array(
                    'config' => require 'acl-routeguard.config.php'
                ),
            ),
            'ZfcAcl\Model\Mapper\EventGuardDefMapConfig' => array(
                'parameters' => array(
                    'config' => require 'acl-eventguard.config.php'
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
                    'routes' => require 'routes.config.php'
                ),
            ),
            
        ),
    ),
);
