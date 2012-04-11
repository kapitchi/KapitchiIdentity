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
        
        //these plugins can be disabled by setting them to false - e.g. 'IdentityAuthCredentialModel' => false
        'plugins' => array(
            //WARNING: Root strategy should not be allowed unless during development on local machine
            'AuthStrategyRoot' => array(
                'enabled' => true,
                'options' => array(
                    'password' => '21232f297a57a5a743894a0e4a801fc3',// md5 hash of the password - default 'admin'
                    'remote_ips' => array(//only localhost is allowed by default
                        '127.0.0.1' => true
                    ),
                ),
            ),
            //used to add credential (username/password) form when editing/creating identity
            'IdentityAuthCredential' => array(
                'enabled' => true,
            ),
            //adds role management for identities - identity form
            'IdentityRole' => array(
                'enabled' => true,
            ),

            //assigns role for registered user
            'RegistrationIdentity' => array(
                'options' => array(
                    'role' => 'user'//this role will be assigned to new identity
                )
            ),

            //DEPENDS ON: RegistrationAuthCredential and Credential auth strategy
            'AuthCredentialEmail' => array(
                'enabled' => false,//replaces username with email field
            ),

            //DEPENDS ON: AuthCredentialEmail
            'AuthCredentialEmailValidation' => array(
                'enabled' => false,//Implements email validation
            ),

            //automatically login after registration
            'RegistrationAuthLogin' => array(
                'enabled' => false,
                'options' => array(
                    'redirect_route' => 'KapitchiIdentity/Profile/Me'//where to redirect user to
                )
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