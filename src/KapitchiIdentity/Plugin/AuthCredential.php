<?php
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
        return 'TODO';
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
        $em = $e->getApplication()->getEventManager();
        $sm = $e->getApplication()->getServiceManager();
        $instance = $this;
        
        $em->getSharedManager()->attach('KapitchiIdentity\Form\Login', 'init', function($e) use ($sm) {
            $e->getTarget()->add($sm->get('KapitchiIdentity\Form\AuthCredentialLogin'), array(
                'name' => 'credential'
            ));
        });
        
        $em->getSharedManager()->attach('KapitchiIdentity\Form\LoginInputFilter', 'init', function($e) use ($sm) {
            $ins = $e->getTarget();
            $ins->add($sm->get('KapitchiIdentity\Form\AuthCredentialLoginInputFilter'), 'credential');
        });
        
        $em->getSharedManager()->attach('KapitchiIdentity\Controller\AuthController', 'login.auth', function($e) use ($sm) {
            $form = $e->getParam('loginForm');
            if($form->isValid()) {
                $data = $form->getData();
                $adapter = new \KapitchiIdentity\Authentication\Adapter\Credential($sm->get('KapitchiIdentity\Mapper\AuthCredentialDbAdapter'));
                $adapter->setPasswordGenerator($sm->get('KapitchiIdentity\PasswordGenerator'));
                $adapter->setIdentity($data['credential']['username']);
                $adapter->setCredential($data['credential']['password']);
                return $adapter;
            }
        });
        
        $em->getSharedManager()->attach('KapitchiIdentity\Controller\AuthController', 'login.auth.post', function($e) use ($sm) {
            $form = $e->getParam('loginForm');
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
        });
    }

}