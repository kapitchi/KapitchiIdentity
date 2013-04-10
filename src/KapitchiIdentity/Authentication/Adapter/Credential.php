<?php
/**
 * Kapitchi Zend Framework 2 Modules (http://kapitchi.com/)
 *
 * @copyright Copyright (c) 2012-2013 Kapitchi Open Source Team (http://kapitchi.com/open-source-team)
 * @license   http://opensource.org/licenses/LGPL-3.0 LGPL 3.0
 */

namespace KapitchiIdentity\Authentication\Adapter;

use Zend\Authentication\Result;
use Zend\Authentication\Adapter\AdapterInterface;
use KapitchiIdentity\Authentication\IdentityResolverInterface;

class Credential implements AdapterInterface, IdentityResolverInterface
{
    protected $identity;
    protected $credential;
    protected $mapper;
    protected $passwordGenerator;
    
    public function __construct($mapper)
    {
        $this->setMapper($mapper);
    }
    
    public function authenticate()
    {
        $mapper = $this->getMapper();
        $user = $mapper->findByUsername($this->getIdentity());
        if(!$user) {
            return new Result(Result::FAILURE_IDENTITY_NOT_FOUND, $this->getIdentity(), array(
                'username' => 'Identity not found'
            ));
        }
        
        if(!$user->getEnabled()) {
            return new Result(Result::FAILURE_UNCATEGORIZED, $this->getIdentity(), array(
                'username' => 'Credential authetication is not enabled'
            ));
        }
        
        $hash = $user->getPasswordHash();
        if(!$this->getPasswordGenerator()->verify($this->getCredential(), $hash)) {
            return new Result(Result::FAILURE_CREDENTIAL_INVALID, $this->getIdentity(), array(
                'password' => 'Password is invalid'
            ));
        }

        return new Result(Result::SUCCESS, $this->getIdentity());
    }
    
    public function getIdentity()
    {
        return $this->identity;
    }

    public function setIdentity($identity)
    {
        $this->identity = $identity;
    }

    public function getCredential()
    {
        return $this->credential;
    }

    public function setCredential($credential)
    {
        $this->credential = $credential;
    }
    
    public function getMapper()
    {
        return $this->mapper;
    }

    public function setMapper($mapper)
    {
        $this->mapper = $mapper;
    }
    
    public function getPasswordGenerator()
    {
        return $this->passwordGenerator;
    }

    public function setPasswordGenerator($passwordGenerator)
    {
        $this->passwordGenerator = $passwordGenerator;
    }

    public function resolveIdentityId($authId)
    {
        $mapper = $this->getMapper();
        $user = $mapper->findByUsername($authId);
        return $user->getIdentityId();
    }

}