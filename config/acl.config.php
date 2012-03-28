<?php

return array(
    'resources' => array(
        'KapitchiIdentity' => array(
            //models
            'KapitchiIdentity/Model/Identity' => null,
            //routes
            'KapitchiIdentity/Route' => null,
            'KapitchiIdentity/Route/Identity' => null,
            'KapitchiIdentity/Route/Auth/Login' => null,
            'KapitchiIdentity/Route/Auth/Logout' => null,
        ),
    ),
    'rules' => array(
        'allow' => array(
            //models
            //TODO XXX mz: finish this - for testing only now!
            'KapitchiIdentity/allow/model/identity' => array('user', 'KapitchiIdentity/Model/Identity'),
            //routes
            'KapitchiIdentity/allow/route/identity' => array('admin', 'KapitchiIdentity/Route/Identity'),
            'KapitchiIdentity/allow/route/auth/logout' => array('auth', 'KapitchiIdentity/Route/Auth/Logout'),
            'KapitchiIdentity/allow/route/auth/login' => array('guest', 'KapitchiIdentity/Route/Auth/Login'),
         ),
        'deny' => array(
            'KapitchiIdentity/deny/route/default_route' => array('guest', 'KapitchiIdentity/Route'),
         ),
    ),
);