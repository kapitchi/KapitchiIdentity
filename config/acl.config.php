<?php

return array(
    'roles' => array(
        'root' => null,
        'guest' => null,
        'auth' => null,
        'user' => 'auth',
        'admin' => 'user',
        'self_registrator' => 'guest',
    ),
    'resources' => array(
        'KapitchiIdentity' => array(
            //services/models
            'KapitchiIdentity\Model\Identity' => null,
            'KapitchiIdentity\Model\Registration' => null,
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
            //root is allowed to do everything on anything
            'KapitchiIdentity/Allow/Root' => array('root', null, null),
            
            'KapitchiIdentity/Allow/Admin' => array('admin', 'KapitchiIdentity'),
            
            //models
            'KapitchiIdentity/Allow/Model/1' => array('self_registrator', 'KapitchiIdentity\Model\Identity', 'persist'),
            'KapitchiIdentity/Allow/Model/2' => array('self_registrator', 'KapitchiIdentity\Model\Registration', 'persist'),
            
            //routes
            'KapitchiIdentity/Allow/Route/2' => array('user', 'KapitchiIdentity/Route/Profile'),
            'KapitchiIdentity/Allow/Route/3' => array('auth', 'KapitchiIdentity/Route/Auth/Logout'),
            'KapitchiIdentity/Allow/Route/4' => array('guest', 'KapitchiIdentity/Route/Auth/Login'),
            'KapitchiIdentity/Allow/Route/5' => array('guest', 'KapitchiIdentity/Route/Registration'),
         ),
        'deny' => array(
            'KapitchiIdentity/Deny/Route/DefaultRoute' => array('guest', 'KapitchiIdentity/Route'),
         ),
    ),
);