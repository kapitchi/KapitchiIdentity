<?php
/**
 * Kapitchi Zend Framework 2 Modules (http://kapitchi.com/)
 *
 * @copyright Copyright (c) 2012-2013 Kapitchi Open Source Team (http://kapitchi.com/open-source-team)
 * @license   http://opensource.org/licenses/LGPL-3.0 LGPL 3.0
 */

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
                'KapitchiIdentity\Controller\Registration' => function($sm) {
                    $cont = new Controller\RegistrationController();
                    $cont->setEntityService($sm->getServiceLocator()->get('KapitchiIdentity\Service\Registration'));
                    $cont->setEntityForm($sm->getServiceLocator()->get('KapitchiIdentity\Form\Registration'));
                    return $cont;
                },
                'KapitchiIdentity\Controller\Auth' => function($sm) {
                    $cont = new Controller\AuthController();
                    $cont->setAuthService($sm->getServiceLocator()->get('KapitchiIdentity\Service\Auth'));
                    $cont->setLoginForm($sm->getServiceLocator()->get('KapitchiIdentity\Form\Login'));
                    return $cont;
                },
                'KapitchiIdentity\Controller\AuthSession' => function($sm) {
                    $cont = new Controller\AuthSessionController();
                    $cont->setSessionProvider($sm->getServiceLocator()->get('KapitchiIdentity\Service\AuthSessionProvider\Session'));
                    $cont->setAuthService($sm->getServiceLocator()->get('KapitchiIdentity\Service\Auth'));
                    return $cont;
                },
                //API
                'KapitchiIdentity\Controller\Api\Identity' => function($sm) {
                    $cont = new Controller\Api\IdentityRestfulController($sm->getServiceLocator()->get('KapitchiIdentity\Service\Identity'));
                    return $cont;
                },
                'KapitchiIdentity\Controller\Api\Auth' => function($sm) {
                    $cont = new Controller\Api\AuthController($sm->getServiceLocator()->get('KapitchiIdentity\Service\Auth'));
                    $cont->setLoginForm($sm->getServiceLocator()->get('KapitchiIdentity\Form\Login'));
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
            'aliases' => array(
                'KapitchiIdentity\Mapper\Identity' => 'KapitchiIdentity\Mapper\IdentityDbAdapter',
                'KapitchiIdentity\Service\AuthSessionProvider' => 'KapitchiIdentity\Service\AuthSessionProvider\Session',
            ),
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
                    if(isset($config['kapitchi-identity']['password_generator_salt'])) {
                        $salt = $config['kapitchi-identity']['password_generator_salt'];
                    }
                    $ins = new \Zend\Crypt\Password\Bcrypt(array(
                        'salt' => $salt,
                    ));
                    return $ins;
                },
                'KapitchiIdentity\Service\Auth' => function ($sm) {
                    $s = new Service\Auth();
                    $s->setContainerHydrator($sm->get('KapitchiIdentity\Model\AuthIdentityContainerHydrator'));
                    $s->setSessionProvider($sm->get('KapitchiIdentity\Service\AuthSessionProvider'));
                    $s->setIdentityMapper($sm->get('KapitchiIdentity\Mapper\Identity'));
                    return $s;
                },
                'KapitchiIdentity\Service\AuthSessionProvider\Session' => function ($sm) {
                    $s = new Service\AuthSessionProvider\Session();
                    return $s;
                },
                'KapitchiIdentity\Model\AuthIdentityContainerHydrator' => function ($sm) {
                    $ins = new Model\AuthIdentityContainerHydrator();
                    $ins->setAuthIdentityHydrator(new Model\AuthIdentityHydrator(false));
                    return $ins;
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
                        $sm->get('KapitchiIdentity\Entity\AuthCredential'),
                        $sm->get('KapitchiIdentity\Entity\AuthCredentialHydrator'),
                        'identity_auth_credential'
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
                'KapitchiIdentity\Form\AuthCredentialRegistration' => function ($sm) {
                    $ins = new Form\AuthCredentialRegistration();
                    $ins->setInputFilter($sm->get('KapitchiIdentity\Form\AuthCredentialRegistrationInputFilter'));
                    return $ins;
                },
                'KapitchiIdentity\Form\AuthCredentialRegistrationInputFilter' => function($sm) {
                    $ins = new Form\AuthCredentialRegistrationInputFilter();
                    return $ins;
                },
                        
                //Identity
                'KapitchiIdentity\Service\Identity' => function ($sm) {
                    $s = new Service\Identity(
                        $sm->get('KapitchiIdentity\Mapper\Identity'),
                        $sm->get('KapitchiIdentity\Entity\Identity'),
                        $sm->get('KapitchiIdentity\Entity\IdentityHydrator')
                    );
                    return $s;
                },
                'KapitchiIdentity\Mapper\IdentityDbAdapter' => function ($sm) {
                    return new Mapper\IdentityDbAdapter(
                        $sm->get('Zend\Db\Adapter\Adapter'),
                        $sm->get('KapitchiIdentity\Entity\Identity'),
                        $sm->get('KapitchiIdentity\Entity\IdentityHydrator'),    
                        'identity'
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
                    $s->setIdentityMapper($sm->get('KapitchiIdentity\Mapper\Identity'));
                    return $s;
                },
                'KapitchiIdentity\Mapper\RegistrationDbAdapter' => function ($sm) {
                    return new Mapper\RegistrationDbAdapter(
                        $sm->get('Zend\Db\Adapter\Adapter'),
                        $sm->get('KapitchiIdentity\Entity\Registration'),
                        $sm->get('KapitchiIdentity\Entity\RegistrationHydrator'),
                        'identity_registration'
                    );
                },
                'KapitchiIdentity\Entity\RegistrationHydrator' => function ($sm) {
                    return new Entity\RegistrationHydrator(false);
                },
                //Registration
                'KapitchiIdentity\Form\Registration' => function($sm) {
                    $ins = new Form\Registration();
                    $ins->setInputFilter($sm->get('KapitchiIdentity\Form\RegistrationInputFilter'));
                    return $ins;
                },
                'KapitchiIdentity\Form\RegistrationInputFilter' => function($sm) {
                    $ins = new Form\RegistrationInputFilter();
                    return $ins;
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