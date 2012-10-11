<?php
return array(
    'KapitchiIdentity' => array(
        'password_generator_salt' => 'this-should-be-at-least-16-char-long-salt'
    ),
    'plugin_manager' => array(
        'invokables' => array(
            'Identity/AuthAccessOnly' => 'KapitchiIdentity\Plugin\AuthAccessOnly',
        ),
        'factories' => array(
            'Identity/AuthCredential' => function($sm) {
                $ins = new \KapitchiIdentity\Plugin\AuthCredential();
                return $ins;
            }
        )
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