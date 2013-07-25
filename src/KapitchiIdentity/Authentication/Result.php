<?php
/**
 * Kapitchi Zend Framework 2 Modules (http://kapitchi.com/)
 *
 * @copyright Copyright (c) 2012-2013 Kapitchi Open Source Team (http://kapitchi.com/open-source-team)
 * @license   http://opensource.org/licenses/LGPL-3.0 LGPL 3.0
 */

namespace KapitchiIdentity\Authentication;

class Result extends \Zend\Authentication\Result
{
    const FAILURE_AUTH_ALREADY = -5;
    const FAILURE_AUTH_DISABLED = -6;
    
    public function __construct($code, $identity, array $messages = array())
    {
        $code = (int) $code;

        //mz: I'm not really sure why this was implemented in Zend. Commenting out.
//        if ($code < self::FAILURE_UNCATEGORIZED) {
//            $code = self::FAILURE;
//        } elseif ($code > self::SUCCESS ) {
//            $code = 1;
//        }

        $this->code     = $code;
        $this->identity = $identity;
        $this->messages = $messages;
    }
}
