<?php

return array(
    'child_map' => array(
        'KapitchiIdentity' => array(
            'default' => 'KapitchiIdentity/Route',
            'child_map' => array(
                'Identity' => array(
                    'default' => 'KapitchiIdentity/Route/Identity',
                    'child_map' => array(
                        'Register' => 'KapitchiIdentity/Route/Identity/Register'
                    )
                ),
                'Profile' => array(
                    'default' => 'KapitchiIdentity/Route/Profile',
                    'child_map' => array(
                    )
                ),
                'Registration' => array(
                    'default' => 'KapitchiIdentity/Route/Registration',
                    'child_map' => array(
                    )
                ),
                'Auth' => array(
                    'child_map' => array(
                        'Login' => 'KapitchiIdentity/Route/Auth/Login',
                        'Logout' => 'KapitchiIdentity/Route/Auth/Logout',
                     )
                 )
             )
        )
    )
);