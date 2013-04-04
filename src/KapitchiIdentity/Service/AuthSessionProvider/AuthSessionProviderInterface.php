<?php
namespace KapitchiIdentity\Service\AuthSessionProvider;

/**
 * 
 * @author Matus Zeman <mz@kapitchi.com>
 */
interface AuthSessionProviderInterface
{
    /**
     * Depending on the concreate implementation this can be e.g. value from session, URL...
     * 
     * @return mixed Returns current auth identity session ID
     */
    public function getCurrentSessionId();
    
    /**
     * If relevant for concrete implementation it clears logged out sessions/auth identities or if true it clears everything
     * 
     * @param array|void $sessionIds
     */
    public function clear($sessionIds = true);
}