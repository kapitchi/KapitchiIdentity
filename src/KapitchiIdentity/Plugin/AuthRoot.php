<?php
/**
 * Kapitchi Zend Framework 2 Modules (http://kapitchi.com/)
 *
 * @copyright Copyright (c) 2012-2013 Kapitchi Open Source Team (http://kapitchi.com/open-source-team)
 * @license   http://opensource.org/licenses/LGPL-3.0 LGPL 3.0
 */

namespace KapitchiIdentity\Plugin;

use Zend\EventManager\EventInterface;
use Zend\Authentication\Result;
use KapitchiApp\PluginManager\PluginInterface;

/**
 *
 * @author Matus Zeman <mz@kapitchi.com>
 */
class AuthRoot implements PluginInterface
{
    protected $adapter;
    
    public function getAuthor()
    {
        return 'Matus Zeman';
    }

    public function getDescription()
    {
        return 'Provides "root" authentication method - password and allowed IPs for which this method is enabled are configurable via config file.';
    }

    public function getName()
    {
        return '[KapitchiIdentity] Root user authentication';
    }

    public function getVersion()
    {
        return '0.1';
    }
    
    public function onBootstrap(EventInterface $e)
    {
        $sm = $e->getApplication()->getServiceManager();
        
        $config = $sm->get('Config');
        if(empty($config['identity_auth_root']['adapter'])) {
            throw new \Exception("config['identity_auth_root']['adapter'] missing");
        }
        
        $moduleConfig = $config['identity_auth_root']['adapter'];
        $adapter = new \KapitchiIdentity\Authentication\Adapter\Root($moduleConfig);
        $this->setAdapter($adapter);

        $this->bootstrapAuthentication($e);
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
                'root' => 'Root'
            )));
            
            //add form
            $form = new \Zend\Form\Form();
            $form->add(array(
                'name' => 'password',
                'type' => 'Zend\Form\Element\Password',
                'options' => array(
                    'label' => 'Root password',
                ),
            ));
            
            $parentForm->add($form, array(
                'name' => 'root'
            ));
        });
        
        $em->getSharedManager()->attach('KapitchiIdentity\Form\LoginInputFilter', 'init', function($e) use ($sm) {
            $ins = $e->getTarget();
            $filter = new \Zend\InputFilter\InputFilter();
            $filter->add(array(
                'name'       => 'password',
                'required'   => true,
            ));
            
            $ins->add($filter, 'root');
        });
        
        $em->getSharedManager()->attach('KapitchiIdentity\Controller\AuthController', 'login.auth', function($e) use ($sm, $instance) {
            $form = $e->getParam('loginForm');
            if($form->getInputFilter()->getValue('method') == 'root') {
                $e->stopPropagation();
                $adapter = $instance->getAdapter();
                $userPassword = $form->getInputFilter()->get('root')->get('password')->getValue();
                $adapter->setCredential($userPassword);
                return $adapter;
            }
        });
        
        $em->getSharedManager()->attach('KapitchiIdentity\Controller\AuthController', 'login.auth.post', function($e) use ($sm) {
            $form = $e->getParam('loginForm');
            
            if($form->get('method')->getValue() == 'root') {
                $result = $e->getParam('result');
                $rootForm = $form->get('root');

                switch ($result->getCode()) {
                    case Result::SUCCESS:
                        //no action
                        break;
                    case Result::FAILURE_CREDENTIAL_INVALID:
                        $rootForm->get('password')->setMessages(array('Invalid password'));
                        break;
                    default:
                        $rootForm->get('password')->setMessages(array("Login error"));
                }
            }
        });
    }

    public function getAdapter()
    {
        return $this->adapter;
    }

    public function setAdapter($adapter)
    {
        $this->adapter = $adapter;
    }

}