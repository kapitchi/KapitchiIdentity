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
    ),
    'plugin_manager' => array(
        'invokables' => array(
            //'Identity/AuthCredential' => 'KapitchiIdentity\Plugin\AuthCredential',
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