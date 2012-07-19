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
        //these plugins can be disabled by setting them to false - e.g. 'IdentityAuthCredential' => false
        'plugin_broker' => array(
            'bootstrap_plugins' => array(
                'AuthStrategyRoot' => true,
                'AuthStrategyCredential' => true,
                'AuthStrategyHttp' => false,
                'AuthStrategyOAuth2' => false,
                'IdentityAuthCredential' => true,
                'IdentityRole' => true,
                'IdentityRegistration' => true,
                'RegistrationIdentity' => true,
                'RegistrationCaptcha' => true,
                'RegistrationAuthCredential' => true,
                'AuthCredentialEmail' => false,
                'AuthCredentialEmailValidation' => false,
                'AuthCredentialForgotPassword' => false,
                'RegistrationAuthLogin' => true,
                'ZfcAcl' => false,
            ),
            
            'specs' => array(
                //WARNING: Root strategy should not be allowed unless during development on local machine
                'AuthStrategyRoot' => array(
                    'options' => array(
                        //'password' => 'TODO-CHANGEME',//md5 hash of the password
                        'remote_ips' => array(//only localhost is allowed by default
                            '127.0.0.1' => true
                        ),
                    ),
                ),
                'AuthStrategyCredential' => array(//username/password strategy
                ),

                'AuthStrategyHttp' => array(//not finished
                    
                ),
                
                'AuthStrategyOAuth2' => array(//NOT FINISHED - outh2 experimental strategy - using Spabby OAuth2 service - https://github.com/Spabby/ZendService-OAuth2
                    'options' => array(
                        //'clientId' => 'TODO-CHANGEME',
                        //'clientSecret' => 'TODO-CHANGEME',
                    )
                ),


                //used to add credential (username/password) form when editing/creating identity
                'IdentityAuthCredential' => array(
                ),
                //adds role management for identities - identity form
                'IdentityRole' => array(
                ),
                //used to remove related registration when identity is deleted
                'IdentityRegistration' => array(
                ),

                //this creates identity for registration, it is set on 100 priority so identity created can be then used other plugins e.g. RegistrationAuthCredential
                'RegistrationIdentity' => array(
                    'options' => array(
                        'role' => 'user'//this role will be assigned to new identity
                    )
                ),

                'RegistrationCaptcha' => array(
                    'options' => array(
                        'captcha_element_options' => array(
                            'label' => "Please verify you're a human",
                            'order' => 100,
                            'required' => true,
                            'captcha' => 'Figlet',
                            'captchaOptions' => array(
                                'captcha' => 'Figlet',
                                'wordLen' => 6,
                                'timeout' => 300,
                            ),
                        )
                    )
                ),

                //adds username/password form to registration form
                'RegistrationAuthCredential' => array(
                ),

                //Extends RegistrationAuthCredential for email/password registration
                //DEPENDS ON: RegistrationAuthCredential and Credential auth strategy
                'AuthCredentialEmail' => array(
                ),

                //Implements email validation
                //DEPENDS ON: AuthCredentialEmail
                'AuthCredentialEmailValidation' => array(
                ),

                //Forgot your password on login form
                //DEPENDS ON: Credential auth strategy
                'AuthCredentialForgotPassword' => array(
                ),

                //automatically login after registration
                'RegistrationAuthLogin' => array(
                    'options' => array(
                        'redirect_route' => 'KapitchiIdentity/Profile/Me'//where to redirect user to
                    )
                ),
            ),
        )

        //SEE BELOW - "DI options" for more options
        
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'kapitchi-identity' => __DIR__ . '/../view',
        ),
        'helper_map' => array(
            //'Zend\Form\View\HelperLoader',
            //'zfcUserIdentity'        => 'ZfcUser\View\Helper\ZfcUserIdentity',
            //'zfcUserLoginWidget'     => 'ZfcUser\View\Helper\ZfcUserLoginWidget',
        ),
    ),
    'router' => array(
        'routes' => require 'routes.config.php'
    ),
    
);