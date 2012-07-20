<?php

namespace KapitchiIdentity;

use Zend\ModuleManager\Feature\ControllerProviderInterface,
    Zend\ModuleManager\Feature\ServiceProviderInterface,
    KapitchiBase\ModuleManager\AbstractModule,
    KapitchiEntity\Mapper\EntityDbAdapterMapperOptions;

class Module extends AbstractModule implements
    ControllerProviderInterface, ServiceProviderInterface
{
    
    public function getControllerConfig()
    {
        return array(
            'invokables' => array(
                //'KapitchiIdentity\Controller\Identity' => 'KapitchiIdentity\Controller\IdentityController',
            ),
            'factories' => array(
                'KapitchiIdentity\Controller\Identity' => function($sm) {
                    $cont = new Controller\IdentityController();
                    $cont->setEntityService($sm->get('KapitchiIdentity\Service\Identity'));
                    $cont->setEntityForm($sm->get('KapitchiIdentity\Form\Identity'));
                    return $cont;
                },
                'KapitchiIdentity\Controller\Auth' => function($sm) {
                    $cont = new Controller\AuthController();
                    $cont->setAuthService($sm->get('KapitchiIdentity\Service\Auth'));
                    $cont->setLoginForm($sm->get('KapitchiIdentity\Form\Login'));
                    return $cont;
                },
            )
        );
    }
    
    public function getServiceConfig()
    {
        return array(
            'invokables' => array(
                //entities
                'KapitchiIdentity\Entity\Identity' => 'KapitchiIdentity\Entity\Identity',
                'KapitchiIdentity\Entity\AuthCredential' => 'KapitchiIdentity\Entity\AuthCredential',
                'KapitchiIdentity\Entity\Registration' => 'KapitchiIdentity\Entity\Registration',
                //forms
                'KapitchiIdentity\Form\Login' => 'KapitchiIdentity\Form\Login',
            ),
            'factories' => array(
                'KapitchiIdentity\Service\Auth' => function ($sm) {
                    $s = new Service\Auth();
                    return $s;
                },
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
                'KapitchiIdentity\Form\Identity' => function ($sm) {
                    return new Form\Identity();
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