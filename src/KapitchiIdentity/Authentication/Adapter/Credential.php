<?php
namespace KapitchiIdentity\Authentication\Adapter;

use Zend\Authentication\Result;

class Credential implements \Zend\Authentication\Adapter\AdapterInterface, \KapitchiIdentity\Authentication\AuthIdentityResolverInterface
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

    public function resolveAuthIdentity($id)
    {
        $mapper = $this->getMapper();
        $user = $mapper->findByUsername($id);
        return new \KapitchiIdentity\Model\AuthIdentity($id, $user->getIdentityId());
    }

}