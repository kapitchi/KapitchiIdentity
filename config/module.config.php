<?php
return array(
    'kapitchi-identity' => array(
        'password_generator_salt' => 'this-should-be-at-least-16-char-long-salt'
    ),
    //KapitchiIdentity\Plugin\AuthRoot
    'identity_auth_root' => array(
        'adapter' => array(
            'allowed_ips' => array('127.0.0.1'),
            'password' => null,//this should be set in app config file
        )
    ),
    'plugin_manager' => array(
        'invokables' => array(
            'Identity/AuthAccessOnly' => 'KapitchiIdentity\Plugin\AuthAccessOnly',
            //'Identity/KapitchiLog' => 'KapitchiIdentity\Plugin\KapitchiLog',
            'Identity/AuthRoot' => 'KapitchiIdentity\Plugin\AuthRoot',
        ),
        'factories' => array(
            'Identity/AuthCredential' => function($sm) {
                $ins = new \KapitchiIdentity\Plugin\AuthCredential();
                return $ins;
            },
            /*'Identity/KapitchiEntityRevision' => function($sm) {
                $ins = new \KapitchiIdentity\Plugin\KapitchiEntityRevision();
                return $ins;
            }*/
        )
    ),
    'router' => array(
        'routes' => require 'routes.config.php'
    ),
);