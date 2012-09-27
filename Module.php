<?php

namespace KapitchiIdentity;

use Zend\EventManager\EventInterface,
    Zend\ModuleManager\Feature\ControllerProviderInterface,
    Zend\ModuleManager\Feature\ServiceProviderInterface,
    Zend\ModuleManager\Feature\ViewHelperProviderInterface,
    KapitchiBase\ModuleManager\AbstractModule,
    KapitchiEntity\Mapper\EntityDbAdapterMapperOptions;

class Module extends AbstractModule implements
    ControllerProviderInterface, ServiceProviderInterface, ViewHelperProviderInterface
{
    
    public function onBootstrap(EventInterface $e)
    {
        $em = $e->getApplication()->getEventManager();
        $sm = $e->getApplication()->getServiceManager();

    }
    
    public function getControllerConfig()
    {
        return array(
            'invokables' => array(
                //'KapitchiIdentity\Controller\Identity' => 'KapitchiIdentity\Controller\IdentityController',
            ),
            'factories' => array(
                'KapitchiIdentity\Controller\Identity' => function($sm) {
                    $cont = new Controller\IdentityController();
                    $cont->setEntityService($sm->getServiceLocator()->get('KapitchiIdentity\Service\Identity'));
                    $cont->setEntityForm($sm->getServiceLocator()->get('KapitchiIdentity\Form\Identity'));
                    return $cont;
                },
                'KapitchiIdentity\Controller\Auth' => function($sm) {
                    $cont = new Controller\AuthController();
                    $cont->setAuthService($sm->getServiceLocator()->get('KapitchiIdentity\Service\Auth'));
                    $cont->setLoginForm($sm->getServiceLocator()->get('KapitchiIdentity\Form\Login'));
                    return $cont;
                },
                //API
                'KapitchiIdentity\Controller\Api\Identity' => function($sm) {
                    $cont = new Controller\Api\IdentityRestfulController($sm->getServiceLocator()->get('KapitchiIdentity\Service\Identity'));
                    return $cont;
                },
            )
        );
    }
    
    public function getViewHelperConfig()
    {
        return array(
            'invokables' => array(
                //'KapitchiIdentity\Controller\Identity' => 'KapitchiIdentity\Controller\IdentityController',
            ),
            'factories' => array(
                'authIdentity' => function($sm) {
                    $ins = new View\Helper\AuthIdentity();
                    $ins->setAuthService($sm->getServiceLocator()->get('KapitchiIdentity\Service\Auth'));
                    $ins->setIdentityService($sm->getServiceLocator()->get('KapitchiIdentity\Service\Identity'));
                    return $ins;
                },
                'identity' => function($sm) {
                    $ins = new View\Helper\Identity($sm->getServiceLocator()->get('KapitchiIdentity\Service\Identity'));
                    return $ins;
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
                'KapitchiIdentity\Form\IdentityInputFilter' => 'KapitchiIdentity\Form\IdentityInputFilter',
            ),
            'factories' => array(
                'KapitchiIdentity\PasswordGenerator' => function ($sm) {
                    $config = $sm->get('Config');
                    $salt = 'TODO-SHOULD-COME-FROM-CONFIG';
                    if(isset($config['KapitchiIdentity']['password_generator_salt'])) {
                        $salt = $config['KapitchiIdentity']['password_generator_salt'];
                    }
                    $ins = new \Zend\Crypt\Password\Bcrypt(array(
                        'salt' => $salt,
                    ));
                    return $ins;
                },
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
                    $s->setPasswordGenerator($sm->get('KapitchiIdentity\PasswordGenerator'));
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
                'KapitchiIdentity\Form\AuthCredential' => function ($sm) {
                    $s = new Form\AuthCredential('auth-credential');
                    return $s;
                },
                'KapitchiIdentity\Form\AuthCredentialInputFilter' => function($sm) {
                    $ins = new Form\AuthCredentialInputFilter();
                    return $ins;
                },
                'KapitchiIdentity\Form\AuthCredentialLogin' => function ($sm) {
                    $ins = new Form\AuthCredentialLogin('auth-credential-login');
                    $ins->setInputFilter($sm->get('KapitchiIdentity\Form\AuthCredentialLoginInputFilter'));
                    return $ins;
                },
                'KapitchiIdentity\Form\AuthCredentialLoginInputFilter' => function($sm) {
                    $ins = new Form\AuthCredentialLoginInputFilter();
                    return $ins;
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
                    return new Entity\IdentityHydrator(false);
                },
                'KapitchiIdentity\Form\Identity' => function ($sm) {
                    $ins = new Form\Identity();
                    $ins->setInputFilter($sm->get('KapitchiIdentity\Form\IdentityInputFilter'));
                    return $ins;
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
                //Login        
                'KapitchiIdentity\Form\Login' => function($sm) {
                    $ins = new Form\Login();
                    $ins->setInputFilter($sm->get('KapitchiIdentity\Form\LoginInputFilter'));
                    return $ins;
                },
                'KapitchiIdentity\Form\LoginInputFilter' => function($sm) {
                    $ins = new Form\LoginInputFilter();
                    return $ins;
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