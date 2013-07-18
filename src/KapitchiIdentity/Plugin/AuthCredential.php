<?php
/**
 * Kapitchi Zend Framework 2 Modules (http://kapitchi.com/)
 *
 * @copyright Copyright (c) 2012-2013 Kapitchi Open Source Team (http://kapitchi.com/open-source-team)
 * @license   http://opensource.org/licenses/LGPL-3.0 LGPL 3.0
 */

namespace KapitchiIdentity\Plugin;

use Zend\EventManager\EventInterface,
    Zend\Authentication\Result,
    KapitchiApp\PluginManager\PluginInterface;

/**
 *
 * @author Matus Zeman <mz@kapitchi.com>
 */
class AuthCredential implements PluginInterface
{
    public function getAuthor()
    {
        return 'Matus Zeman';
    }

    public function getDescription()
    {
        return 'Provides username/password authentication';
    }

    public function getName()
    {
        return '[KapitchiIdentity] Credential authentication method';
    }

    public function getVersion()
    {
        return '0.1';
    }
    
    public function onBootstrap(EventInterface $e)
    {
        $this->bootstrapIdentityManagement($e);
        $this->bootstrapAuthentication($e);
        $this->bootstrapRegistration($e);
    }
    
    protected function bootstrapIdentityManagement($e)
    {
        $em = $e->getApplication()->getEventManager();
        $sm = $e->getApplication()->getServiceManager();
        $instance = $this;

        $em->getSharedManager()->attach('KapitchiIdentity\Form\Identity', 'init', function($e) use ($sm) {
            $form = $sm->get('KapitchiIdentity\Form\AuthCredential');
            $e->getTarget()->add($form, array(
                'name' => 'auth_credential'
            ));
        });
        $em->getSharedManager()->attach('KapitchiIdentity\Form\IdentityInputFilter', 'init', function($e) use ($sm) {
            $ins = $e->getTarget();
            $authCredentialInputFilter = clone $sm->get('KapitchiIdentity\Form\AuthCredentialInputFilter');
            $ins->add($authCredentialInputFilter, 'auth_credential');
        });
        
        $em->getSharedManager()->attach('KapitchiIdentity\Form\Identity', 'setData', function($e) use ($sm) {
            $form = $e->getTarget();
            $data = $e->getParam('data');
            $id = $form->get('id')->getValue();
            
            if($id && empty($data['auth_credential'])) {
                $ser = $sm->get('KapitchiIdentity\Service\AuthCredential');
                $authEntity = $ser->findOneBy(array('identityId' => $id));
                if($authEntity) {
                    $form->setData(array(
                        'auth_credential' => $ser->createArrayFromEntity($authEntity)
                    ));
                }
            }
        });
        
        $em->getSharedManager()->attach('KapitchiIdentity\Controller\IdentityController', 'update.pre', function($e) use ($sm) {
            $form = $e->getParam('form');
            $inputFilter = $form->getInputFilter();
            $cred = $inputFilter->get('auth_credential');
            $cred->get('password')->setAllowEmpty(true);
            $cred->get('passwordConfirm')->setAllowEmpty(true);
        });
        
        $em->getSharedManager()->attach('KapitchiIdentity\Service\Identity', 'persist', function($e) use ($sm) {
            $data = $e->getParam('data');
            if(!empty($data['auth_credential'])) {
                $ser = $sm->get('KapitchiIdentity\Service\AuthCredential');
                $entity = $ser->createEntityFromArray($data['auth_credential']);
                $entity->setIdentityId($e->getParam('entity')->getId());
                $ser->persist($entity, $data['auth_credential']);
            }
        });
    }
    
    protected function bootstrapAuthentication($e)
    {
        $em = $e->getApplication()->getEventManager();
        $sm = $e->getApplication()->getServiceManager();
        $instance = $this;

        $em->getSharedManager()->attach('KapitchiIdentity\Form\Login', 'init', function($e) use ($sm) {
            $parentForm = $e->getTarget();
            $method = $parentForm->get('method');
            
            //add auth option
            $method->setValueOptions(array_merge($method->getValueOptions(), array(
                'credential' => 'Credential'
            )));
            
            $form = $sm->get('KapitchiIdentity\Form\AuthCredentialLogin');
            $parentForm->add($form, array(
                'name' => 'credential'
            ));
        });
        
        $em->getSharedManager()->attach('KapitchiIdentity\Form\LoginInputFilter', 'init', function($e) use ($sm) {
            $ins = $e->getTarget();
            $ins->add($sm->get('KapitchiIdentity\Form\AuthCredentialLoginInputFilter'), 'credential');
        });
        
        $em->getSharedManager()->attach('KapitchiIdentity\Controller\AuthController', 'login.auth', function(EventInterface $e) use ($sm) {
            $form = $e->getParam('loginForm');
            if($form->getInputFilter()->getValue('method') == 'credential') {
                $data = $form->getData();
                
                //TODO mz: to service manager
                $adapter = new \KapitchiIdentity\Authentication\Adapter\Credential($sm->get('KapitchiIdentity\Mapper\AuthCredentialDbAdapter'));
                $adapter->setPasswordGenerator($sm->get('KapitchiIdentity\PasswordGenerator'));
                $adapter->setIdentity($data['credential']['username']);
                $adapter->setCredential($data['credential']['password']);
                
                $e->stopPropagation();
                return $adapter;
            }
        });
        
        $em->getSharedManager()->attach('KapitchiIdentity\Controller\AuthController', 'login.auth.post', function($e) use ($sm) {
            $form = $e->getParam('loginForm');
            
            if($form->get('method')->getValue() == 'credential') {
                $result = $e->getParam('result');
                $credential = $form->get('credential');

                switch ($result->getCode()) {
                    case Result::SUCCESS:
                        //no action
                        break;
                    case Result::FAILURE_IDENTITY_NOT_FOUND:
                        $credential->get('username')->setMessages(array('User not found'));
                        break;
                    case Result::FAILURE_CREDENTIAL_INVALID:
                        $credential->get('password')->setMessages(array('Invalid password'));
                        break;
                    default:
                        $credential->get('username')->setMessages(array("Login error"));
                }
            }
        });
        
    }
    
    protected function bootstrapRegistration(EventInterface $e)
    {
        $em = $e->getApplication()->getEventManager();
        $sm = $e->getApplication()->getServiceManager();
        $instance = $this;
        
        $em->getSharedManager()->attach('KapitchiIdentity\Form\Registration', 'init', function($e) use ($sm) {
            $form = $sm->get('KapitchiIdentity\Form\AuthCredentialRegistration');
            $e->getTarget()->add($form, array(
                'name' => 'auth_credential'
            ));
        });
        
        $em->getSharedManager()->attach('KapitchiIdentity\Form\RegistrationInputFilter', 'init', function($e) use ($sm) {
            $ins = $e->getTarget();
            $authCredentialInputFilter = $sm->get('KapitchiIdentity\Form\AuthCredentialRegistrationInputFilter');
            $ins->add($authCredentialInputFilter, 'auth_credential');
        });
        
        $em->getSharedManager()->attach('KapitchiIdentity\Service\Registration', 'register.post', function($e) use ($sm) {
            $data = $e->getParam('data');
            if(!empty($data['auth_credential'])) {
                $ser = $sm->get('KapitchiIdentity\Service\AuthCredential');
                $authData = $data['auth_credential'];
                $authData['enabled'] = true;
                $authData['identityId'] = $e->getParam('identity')->getId();
                $ser->persist($authData);
            }
        });
    }

}