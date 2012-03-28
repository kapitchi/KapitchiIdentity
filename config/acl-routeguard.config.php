<?php

return array(
    'child_map' => array(
        'KapitchiIdentity' => array(
            'default' => 'KapitchiIdentity/Route',
            'child_map' => array(
                'Identity' => 'KapitchiIdentity/Route/Identity',
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