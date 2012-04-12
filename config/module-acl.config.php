<?php
return array(
    'KapitchiIdentity' => array(
        'options' => array(
        ),
        
        //these plugins can be disabled by setting them to false - e.g. 'IdentityAuthCredentialModel' => false
        'plugins' => array(
            //ZfcAcl module does not manage roles itself - it relies on other modules to provide it - this plugin does exactly this
            'ZfcAcl' => array(
                'diclass' => 'KapitchiIdentityAcl\Plugin\ZfcAcl',
                'options' => array(
                    'resource_loader' => array(
                        'enabled' => false
                    )
                )
            )
        )

        //SEE BELOW - "DI options" for more options
        
    ),
    'di' => array(
        'instance' => array(
            //ZfcAcl module settings
            'ZfcAcl\Service\Acl' => array(//role provider
                'parameters' => array(
                    'roleProvider' => 'KapitchiIdentityAcl\Service\RoleProvider'
                ),
            ),
            'ZfcAcl\Model\Mapper\RouteResourceMapConfig' => array(
                'parameters' => array(
                    'config' => require 'acl-routeguard.config.php'
                ),
            ),
            'ZfcAcl\Model\Mapper\EventGuardDefMapConfig' => array(
                'parameters' => array(
                    'config' => require 'acl-eventguard.config.php'
                )
            ),
            'ZfcAcl\Model\Mapper\AclLoaderConfig' => array(
                'parameters' => array(
                    'config' => require 'acl.config.php'
                ),
            ),
            //END - ZfcAcl
            
            //services
            'KapitchiIdentityAcl\Service\RoleProvider' => array(
                'parameters' => array(
                    'identityRoleService' => 'KapitchiIdentity\Service\IdentityRole'
                ),
            ),
            'KapitchiIdentityAcl\Service\ResourceLoader' => array(
                'parameters' => array(
                    'identityRoleService' => 'KapitchiIdentity\Service\IdentityRole',
                    'resourceLoaderDefMapper' => 'KapitchiIdentityAcl\Model\Mapper\ResourceLoaderDefConfig',
                )
            ),
            
            //mappers
            'KapitchiIdentityAcl\Model\Mapper\ResourceLoaderDefConfig' => array(
                'parameters' => array(
                    'config' => require 'acl-resourceloader.config.php'
                )
            ),
            
        ),
    ),
);
