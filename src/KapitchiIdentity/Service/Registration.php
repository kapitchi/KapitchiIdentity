<?php
/**
 * Kapitchi Zend Framework 2 Modules (http://kapitchi.com/)
 *
 * @copyright Copyright (c) 2012-2013 Kapitchi Open Source Team (http://kapitchi.com/open-source-team)
 * @license   http://opensource.org/licenses/LGPL-3.0 LGPL 3.0
 */

namespace KapitchiIdentity\Service;

use KapitchiEntity\Service\EntityService;

use Zend\Http\PhpEnvironment\RemoteAddress;

class Registration extends EntityService
{
    protected $remoteAddress;
    protected $identityService;
    /**
     * Default data to create an identity entity with
     * @var array
     */
    protected $defaultIdentityData = array(
        'authEnabled' => true
    );
    
    public function register(array $data) {
        $data['requestIp'] = $this->getRemoteAddress()->getIpAddress();
        $data['created'] = new \DateTime();
        
        $params = array(
            'data' => $data,
        );
        
        $event = new \Zend\EventManager\Event('register.pre', $this, $params);
        $this->getEventManager()->trigger($event);
        
        $persistEvent = $this->persist($data);
        
        $event->setName('register.post');
        $event->setParam('persistEvent', $persistEvent);
        //identity is implemented using event listener - so it might be interrupted
        $this->getEventManager()->trigger($event);
        return $event;
    }
    
    protected function attachDefaultListeners()
    {
        parent::attachDefaultListeners();
        $em = $this->getEventManager();
        $em->attach('register.post', function($e) {
            $target = $e->getTarget();
            
            $idService = $target->getIdentityService();
            
            $data = array_merge(array(
                'created' => new \DateTime(),
            ), $target->getDefaultIdentityData());
            $identityEvent = $idService->persist($data);
            
            $identity = $identityEvent->getEntity();
            $e->setParam('identity', $identity);
            $registration = $e->getParam('persistEvent')->getEntity();
            $registration->setIdentityId($identity->getId());
            $target->getMapper()->persist($registration);
        });
    }

    public function getIdentityService()
    {
        return $this->identityService;
    }

    public function setIdentityService($identityService)
    {
        $this->identityService = $identityService;
    }

    public function getRemoteAddress()
    {
        if($this->remoteAddress === null) {
            $this->remoteAddress = new RemoteAddress();
        }
        return $this->remoteAddress;
    }

    public function setRemoteAddress(RemoteAddress $remoteAddress)
    {
        $this->remoteAddress = $remoteAddress;
    }
    
    public function getDefaultIdentityData()
    {
        return $this->defaultIdentityData;
    }

    public function setDefaultIdentityData(array $defaultIdentityData)
    {
        $this->defaultIdentityData = $defaultIdentityData;
    }

}