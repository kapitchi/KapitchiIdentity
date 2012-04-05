<?php
return array(
    'KapitchiIdentity' => array(
        'options' => array(
            'identity' => array(
                'view' => array(
                    'item_count_per_page' => 10,//identity index page - number of identies to render per page
                )
            ),
        ),
        
        //these plugins can be disabled by setting them to false - e.g. 'IdentityAuthCredentialModel' => false
        'plugins' => array(
            //WARNING: Root strategy should not be allowed unless during development on local machine
            'AuthStrategyRoot' => array(
                'diclass' => 'KapitchiIdentity\Plugin\AuthStrategy\Root',
                'options' => array(
                    //'password' => 'TODO-CHANGEME',//md5 hash of the password
                    'remote_ips' => array(//only localhost is allowed by default
                        '127.0.0.1' => true
                    ),
                ),
            ),
            'AuthStrategyCredential' => array(
                'diclass' => 'KapitchiIdentity\Plugin\AuthStrategy\Credential',//username/password strategy
            ),
//            'AuthStrategyHttp' => array(
//                'diclass' => 'KapitchiIdentity\Plugin\AuthStrategy\Http', //NOT FINISHED
//            ),
//            'AuthStrategyOAuth2' => array(
//                'diclass' => 'KapitchiIdentity\Plugin\AuthStrategy\OAuth2',//NOT FINISHED - outh2 experimental strategy - using Spabby OAuth2 service - https://github.com/Spabby/ZendService-OAuth2
//            ),
            
            
            //used to add credential (username/password) form when editing/creating identity
            'IdentityAuthCredential' => array(
                'diclass' => 'KapitchiIdentity\Plugin\IdentityAuthCredential',
            ),
            //adds role management for identities - identity form
            'IdentityRole' => array(
                'diclass' => 'KapitchiIdentity\Plugin\IdentityRole',
            ),

            //this creates identity for registration, it is set on 100 priority so identity created can be then used other plugins e.g. RegistrationAuthCredential
            'RegistrationIdentity' => array(
                'diclass' => 'KapitchiIdentity\Plugin\RegistrationIdentity',
                'options' => array(
                    'role' => 'user'//this role will be assigned to new identity
                )
            ),

            //adds username/password form to registration form
            'RegistrationAuthCredential' => array(
                'diclass' => 'KapitchiIdentity\Plugin\RegistrationAuthCredential',
            ),

            //Extends RegistrationAuthCredential for email/password registration
            //DEPENDS ON: RegistrationAuthCredential and Credential auth strategy
            'AuthCredentialEmail' => array(
                'diclass' => 'KapitchiIdentity\Plugin\AuthCredentialEmail',
            ),

            //Implements email validation
            //DEPENDS ON: AuthCredentialEmail
            'AuthCredentialEmailValidation' => array(
                'diclass' => 'KapitchiIdentity\Plugin\AuthCredentialEmailValidation',
            ),

            //Forgot your password on login form
            //DEPENDS ON: Credential auth strategy
            'AuthCredentialForgotPassword' => array(
                'diclass' => 'KapitchiIdentity\Plugin\AuthCredentialForgotPassword',
            ),

            //automatically login after registration
            'RegistrationAuthLogin' => array(
                'diclass' => 'KapitchiIdentity\Plugin\RegistrationAuthLogin',
                'options' => array(
                    'redirect_route' => 'KapitchiIdentity/Profile/Me'//where to redirect user to
                )
            ),

            //ZfcAcl module does not manage roles itself - it relies on other modules to provide it - this plugin does exactly this
            'ZfcAcl' => array(
                'diclass' => 'KapitchiIdentity\Plugin\ZfcAcl',
            )
        )

        //SEE BELOW - "DI options" for more options
        
    ),
    'di' => array(
        'instance' => array(
            
            //DI options
            'KapitchiIdentity-auth_credential_password_hash' => array(
                'parameters' => array(
                    'sharedSalt' => 'TODO-CHANGE-ME',//this is "shared salt" used to prefix all passwords and then encrypted to add additional protection
                    'algorithm' => 'blowfish',//algorithm to be used to encrypt passwords - 'md5', 'blowfish', 'sha256', 'sha512'
                    'cost' => 0,//used to set rounds param for e.g. sha256, ... see http://php.net/manual/en/function.crypt.php 
                )
            ),
            'alias' => array(
                //'KapitchiIdentity-db_adapter' => 'Zend\Db\Adapter\Adapter',//sets Zend\Db\Adapter\Adapter instance to be used with KapitchiIdentity module
                
                //DO NOT MODIFY below
                'KapitchiIdentity-auth_credential_password_hash' => 'KapitchiBase\Crypt\Hash',
            ),
            
            //END - DI options
            
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
                    'registerViewModel' => 'KapitchiIdentity\View\Model\RegistrationRegister',
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
                    'passwordHash' => 'KapitchiIdentity-auth_credential_password_hash',
                ),
            ),
            'KapitchiIdentity\Service\Registration' => array(
                'parameters' => array(
                    'aclContextService' => 'ZfcAcl\Service\Context',
                    'mapper' => 'KapitchiIdentity\Model\Mapper\RegistrationDbAdapter',
                    'identityMapper' => 'KapitchiIdentity\Model\Mapper\IdentityDbAdapter',
                    'identityRoleMapper' => 'KapitchiIdentity\Model\Mapper\IdentityRoleDbAdapter',
                    'modelPrototype' => 'KapitchiIdentity\Model\Registration',
                )
            ),
            'KapitchiIdentity\Service\IdentityRegistration' => array(
                'parameters' => array(
                    'mapper' => 'KapitchiIdentity\Model\Mapper\IdentityRegistrationDbAdapter',
                    'modelPrototype' => 'KapitchiIdentity\Model\IdentityRegistration',
                )
            ),
            //auth strategies
            'KapitchiIdentity\Plugin\AuthStrategy\Credential' => array(
                'parameters' => array(
                    'credentialMapper' => 'KapitchiIdentity\Model\Mapper\AuthCredentialDbAdapter',
                    'passwordHash' => 'KapitchiIdentity-auth_credential_password_hash',
                    'credentialLoginForm' => 'KapitchiIdentity\Form\AuthCredential\Login',
                ),
            ),
            'KapitchiIdentity\Plugin\AuthStrategy\OAuth2' => array(
                'parameters' => array(
                    'OAuth2LoginForm' => 'KapitchiIdentity\Form\OAuth2\Login',
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
            //role provider
            'ZfcAcl\Service\Acl' => array(
                'parameters' => array(
                    'roleProvider' => 'KapitchiIdentity\Plugin\ZfcAcl\RoleProvider'
                ),
            ),
            'KapitchiIdentity\Plugin\ZfcAcl\RoleProvider' => array(
                'parameters' => array(
                    'identityRoleService' => 'KapitchiIdentity\Service\IdentityRole'
                ),
            ),
            
            'ZfcAcl\Model\Mapper\AclLoaderConfig' => array(
                'parameters' => array(
                    'config' => require 'acl.config.php'
                ),
            ),
            
            'KapitchiIdentity\Plugin\ZfcAcl\ResourceLoader' => array(
                'parameters' => array(
                    'identityRoleService' => 'KapitchiIdentity\Service\IdentityRole',
                    'resourceLoaderDefMapper' => 'KapitchiIdentity\Plugin\ZfcAcl\Model\Mapper\ResourceLoaderDefConfig',
                )
            ),
            'KapitchiIdentity\Plugin\ZfcAcl\Model\Mapper\ResourceLoaderDefConfig' => array(
                'parameters' => array(
                    'config' => require 'acl-resourceloader.config.php'
                )
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
