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

class Root implements AdapterInterface, IdentityResolverInterface
{
    protected $allowedIps = array();
    protected $password;
    protected $credential;
    
    public function __construct(array $options)
    {
        if(empty($options['allowed_ips']) && !is_array($options['allowed_ips'])) {
            throw new \RuntimeException('allowed_ips needs to be non empty array');
        }
        if(empty($options['password'])) {
            throw new \RuntimeException('password needs to be non empty string');
        }
        
        $this->setAllowedIps($options['allowed_ips']);
        $this->setPassword($options['password']);
    }
    
    public function authenticate()
    {
        $address = new \Zend\Http\PhpEnvironment\RemoteAddress();
        $ip = $address->getIpAddress();
        if(!in_array($ip, $this->getAllowedIps())) {
            return new Result(Result::FAILURE, $this->getIdentity(), array(
                'allowedIps' => 'Not allowed IP'
            ));
        }
        
        if($this->getPassword() !== $this->getCredential()) {
            return new Result(Result::FAILURE_CREDENTIAL_INVALID, $this->getIdentity(), array(
                'password' => 'Password is invalid'
            ));
        }

        return new Result(Result::SUCCESS, $this->getIdentity());
    }
    
    public function getIdentity() {
        return 'root';
    }
    
    public function getCredential()
    {
        return $this->credential;
    }

    public function setCredential($credential)
    {
        $this->credential = $credential;
    }
    
    public function getAllowedIps()
    {
        return $this->allowedIps;
    }

    public function setAllowedIps(array $allowedIps)
    {
        $this->allowedIps = $allowedIps;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }
    
    public function resolveIdentityId($authId)
    {
        //identity #1 is hardcoded number for root user
        return 1;
    }

}