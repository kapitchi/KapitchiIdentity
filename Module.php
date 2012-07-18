<?php

namespace KapitchiIdentity;

use Zend\ModuleManager\Feature\ControllerProviderInterface,
    Zend\ModuleManager\Feature\ServiceProviderInterface,
    KapitchiBase\ModuleManager\AbstractModule,
    KapitchiEntity\Mapper\EntityDbAdapterMapperOptions;

class Module extends AbstractModule implements
    ControllerProviderInterface, ServiceProviderInterface
{
    
    public function getControllerConfiguration()
    {
        return array(
            'factories' => array(
//                'KapitchiIdentity\Controller\Item' => function($sm) {
//                    $cont = new Controller\ItemController();
//                    $cont->setEntityService($sm->get('KapitchiIdentity\Service\Item'));
//                    $cont->setEntityForm($sm->get('KapitchiIdentity\Form\Item'));
//                    return $cont;
//                },
            )
        );
    }
    
    public function getServiceConfiguration()
    {
        return array(
            'invokables' => array(
                'KapitchiIdentity\Entity\AuthCredential' => 'KapitchiIdentity\Entity\AuthCredential',
            ),
            'factories' => array(
                //AuthCredential
                'KapitchiIdentity\Service\AuthCredential' => function ($sm) {
                    $s = new Service\AuthCredential(
                        $sm->get('KapitchiIdentity\Mapper\AuthCredentialDbAdapter'),
                        $sm->get('KapitchiIdentity\Entity\AuthCredential'),
                        $sm->get('KapitchiIdentity\Entity\AuthCredentialHydrator')
                    );
                    //$s->setInputFilter($sm->get('KapitchiIdentity\Entity\AuctionInputFilter'));
                    return $s;
                },
                'KapitchiIdentity\Mapper\AuthCredentialDbAdapter' => function ($sm) {
                    return new Mapper\AuthCredentialDbAdapter(
                        $sm->get('Zend\Db\Adapter\Adapter'),
                        new EntityDbAdapterMapperOptions(array(
                            'tableName' => 'identity_auth_credential',
                            'primaryKey' => 'id',
                            'hydrator' => $sm->get('KapitchiIdentity\Entity\AuthCredentialHydrator'),
                            'entityPrototype' => $sm->get('KapitchiIdentity\Entity\AuthCredential'),
                        ))
                    );
                },
                'KapitchiIdentity\Entity\AuthCredentialHydrator' => function ($sm) {
                    //needed here because hydrator tranforms camelcase to underscore
                    return new \Zend\Stdlib\Hydrator\ClassMethods(false);
                },
                //Identity
                'KapitchiIdentity\Service\Identity' => function ($sm) {
                    $s = new Service\Identity(
                        $sm->get('KapitchiIdentity\Mapper\IdentityDbAdapter'),
                        $sm->get('KapitchiIdentity\Entity\Identity'),
                        $sm->get('KapitchiIdentity\Entity\IdentityHydrator')
                    );
                    return $s;
                },
                'KapitchiIdentity\Mapper\IdentityDbAdapter' => function ($sm) {
                    return new Mapper\IdentityDbAdapter(
                        $sm->get('Zend\Db\Adapter\Adapter'),
                        new EntityDbAdapterMapperOptions(array(
                            'tableName' => 'identity',
                            'primaryKey' => 'id',
                            'hydrator' => $sm->get('KapitchiIdentity\Entity\IdentityHydrator'),
                            'entityPrototype' => $sm->get('KapitchiIdentity\Entity\Identity'),
                        ))
                    );
                },
                'KapitchiIdentity\Entity\IdentityHydrator' => function ($sm) {
                    //needed here because hydrator tranforms camelcase to underscore
                    return new \Zend\Stdlib\Hydrator\ClassMethods(false);
                },
                //Registration
                'KapitchiIdentity\Service\Registration' => function ($sm) {
                    $s = new Service\Registration(
                        $sm->get('KapitchiIdentity\Mapper\RegistrationDbAdapter'),
                        $sm->get('KapitchiIdentity\Entity\Registration'),
                        $sm->get('KapitchiIdentity\Entity\RegistrationHydrator')
                    );
                    return $s;
                },
                'KapitchiIdentity\Mapper\RegistrationDbAdapter' => function ($sm) {
                    return new Mapper\RegistrationDbAdapter(
                        $sm->get('Zend\Db\Adapter\Adapter'),
                        new EntityDbAdapterMapperOptions(array(
                            'tableName' => 'identity_registration',
                            'primaryKey' => 'id',
                            'hydrator' => $sm->get('KapitchiIdentity\Entity\RegistrationHydrator'),
                            'entityPrototype' => $sm->get('KapitchiIdentity\Entity\Registration'),
                        ))
                    );
                },
                'KapitchiIdentity\Entity\RegistrationHydrator' => function ($sm) {
                    //needed here because hydrator tranforms camelcase to underscore
                    return new \Zend\Stdlib\Hydrator\ClassMethods(false);
                },
            )
        );
    }
    
    public function getDir() {
        return __DIR__;
    }
    
    public function getNamespace() {
        return __NAMESPACE__;
    }
}