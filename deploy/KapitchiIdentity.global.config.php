<?php
/**
 * Local Configuration Override for KapitchiIdentity
 *
 * This file is shipped to allow easy setup of KapitchiIdentity with the ZendSkeletonApplication
 * Application module.
 *
 * This configuration override file is for overriding environment-specific and
 * security-sensitive configuration information. Copy this file without the
 * .dist extension at the end and populate values as needed.
 */
return array(
    'KapitchiIdentity' => array(
        'options' => array(
            'identity' => array(
                'view' => array(
                    'item_count_per_page' => 10,//identity index page - number of identies to render per page
                )
            ),
        ),
        
        //these plugins can be disabled by setting them to false - e.g. 'IdentityAuthCredential' => false
        'plugin_broker' => array(
            'bootstrap_plugins' => array(
                'AuthStrategyRoot' => true,//WARNING: Root strategy should not be allowed unless during development on local machine
                'AuthStrategyCredential' => true,//username/password login
                'AuthStrategyHttp' => false,//TODO not finished - needs cleaning up and options
                'AuthStrategyOAuth2' => false,//TODO not finished - waiting for Spabby
                'IdentityAuthCredential' => true,//adds credential (username/password) form when editing/creating identity
                'IdentityRole' => true,//adds role management for identities - identity form
                'RegistrationCaptcha' => true,//implements captcha for registration form
                'RegistrationAuthCredential' => true,//adds username/password form to registration
                'AuthCredentialEmail' => false,//replaces username with email field - DEPENDS ON: RegistrationAuthCredential and AuthStrategyCredential auth strategy
                'AuthCredentialEmailValidation' => false,//implements email validation - DEPENDS ON: AuthCredentialEmail
                'AuthCredentialForgotPassword' => false,//TODO not finished - password recovery
                'RegistrationAuthLogin' => true,//auto-login after registering
                
                //KapitchiIdentityAcl
                'ZfcAcl' => false,//DEPENDS ON: ZfcAcl module
            ),
            'specs' => array(
                'AuthStrategyRoot' => array(
                    'options' => array(
                        'password' => '21232f297a57a5a743894a0e4a801fc3',// md5 hash of the password - default 'admin'
                        'remote_ips' => array(//only localhost is allowed by default
                            '127.0.0.1' => true
                        ),
                    ),
                ),
                'RegistrationIdentity' => array(
                    'options' => array(
                        'role' => 'user'//this role will be assigned to new identity
                    )
                ),
                'RegistrationAuthLogin' => array(
                    'options' => array(
                        'redirect_route' => 'KapitchiIdentity/Profile/Me'//where to redirect user to
                    )
                ),
                'RegistrationCaptcha' => array(
                    'options' => array(
                        'captcha_element_options' => array(
                            'label' => "Please verify you're a human",
                            'captcha' => 'Figlet',
                            'captchaOptions' => array(
                                'captcha' => 'Figlet',
                                'wordLen' => 6,
                                'timeout' => 300,
                            ),
                        )
                    )
                ),
            ),
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
                'KapitchiIdentity-db_adapter' => 'Zend\Db\Adapter\Adapter',//sets Zend\Db\Adapter\Adapter instance as DB adapter with KapitchiIdentity module
            ),
        ),
    ),
);