<?php
/**
 * Kapitchi Zend Framework 2 Modules (http://kapitchi.com/)
 *
 * @copyright Copyright (c) 2012-2013 Kapitchi Open Source Team (http://kapitchi.com/open-source-team)
 * @license   http://opensource.org/licenses/LGPL-3.0 LGPL 3.0
 */

namespace KapitchiIdentity\Service\AuthSessionProvider;

use Zend\Session\Container as SessionContainer;

/**
 * @todo this whole class needs more flexibility?
 * @author Matus Zeman <mz@kapitchi.com>
 */
class Session implements AuthSessionProviderInterface
{
    protected $session;
            
    public function __construct()
    {
        $this->session = new SessionContainer(__CLASS__);
    }
            
    public function getCurrentSessionId()
    {
        return $this->session->currentSessionId;
    }
    
    public function setCurrentSessionId($id)
    {
        $this->session->currentSessionId = $id;
    }

    public function clear($sessionIds = true)
    {
        if($sessionIds === true) {
            $this->session->currentSessionId = null;
            return;
        }
        
        if(in_array($this->getCurrentSessionId(), $sessionIds)) {
            $this->session->currentSessionId = null;
        }
    }
    
}