<?php

return array(
    'resources' => array(
        'KapitchiIdentity' => array(
            //services
            'KapitchiIdentity/Service/Identity' => null,
            //routes
            'KapitchiIdentity/Route' => null,
            'KapitchiIdentity/Route/Profile' => null,
            'KapitchiIdentity/Route/Identity' => null,
            'KapitchiIdentity/Route/Registration' => null,
            'KapitchiIdentity/Route/Auth/Login' => null,
            'KapitchiIdentity/Route/Auth/Logout' => null,
        ),
    ),
    'rules' => array(
        'allow' => array(
            //models
            //TODO XXX mz: finish this - for testing only now!
            'KapitchiIdentity/Allow/Service/Identity' => array('user', 'KapitchiIdentity/Service/Identity'),
            
            //routes
            'KapitchiIdentity/Allow/Route/Identity' => array('admin', 'KapitchiIdentity/Route/Identity'),
            'KapitchiIdentity/Allow/Route/Profile' => array('user', 'KapitchiIdentity/Route/Profile'),
            'KapitchiIdentity/Allow/Route/Auth/Logout' => array('auth', 'KapitchiIdentity/Route/Auth/Logout'),
            'KapitchiIdentity/Allow/Route/Auth/Login' => array('guest', 'KapitchiIdentity/Route/Auth/Login'),
            
            //registration
            'KapitchiIdentity/Allow/Route/Registration' => array('guest', 'KapitchiIdentity/Route/Registration'),
         ),
        'deny' => array(
            'KapitchiIdentity/Deny/Route/DefaultRoute' => array('guest', 'KapitchiIdentity/Route'),
         ),
    ),
);