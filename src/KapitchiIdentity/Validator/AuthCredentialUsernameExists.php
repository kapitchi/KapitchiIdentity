<?php

/**
 * Kapitchi Zend Framework 2 Modules (http://kapitchi.com/)
 *
 * @copyright Copyright (c) 2012-2013 Kapitchi Open Source Team (http://kapitchi.com/open-source-team)
 * @license   http://opensource.org/licenses/LGPL-3.0 LGPL 3.0
 */

namespace KapitchiIdentity\Validator;

use KapitchiIdentity\Mapper\AuthCredentialInterface;

/**
 * @author Matus Zeman <mz@kapitchi.com>
 */
class AuthCredentialUsernameExists extends \Zend\Validator\AbstractValidator
{
    protected $messageTemplates = array(
        'usernameExists' => "Username exists",
    );
    
    protected $authCredentialMapper;
    
    public function __construct(AuthCredentialInterface $mapper, $options = null)
    {
        parent::__construct($options);
        $this->setAuthCredentialMapper($mapper);
    }
    
    public function isValid($value)
    {
        if($this->getAuthCredentialMapper()->findByUsername($value)) {
            $this->error('usernameExists');
            return false;
        }
        
        return true;
    }
    
    /**
     * 
     * @return \KapitchiIdentity\Mapper\AuthCredentialInterface
     */
    public function getAuthCredentialMapper()
    {
        return $this->authCredentialMapper;
    }

    public function setAuthCredentialMapper(\KapitchiIdentity\Mapper\AuthCredentialInterface $authCredentialMapper)
    {
        $this->authCredentialMapper = $authCredentialMapper;
    }

}