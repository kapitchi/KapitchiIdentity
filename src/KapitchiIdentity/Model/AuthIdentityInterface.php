<?php
/**
 * Kapitchi Zend Framework 2 Modules (http://kapitchi.com/)
 *
 * @copyright Copyright (c) 2012-2013 Kapitchi Open Source Team (http://kapitchi.com/open-source-team)
 * @license   http://opensource.org/licenses/LGPL-3.0 LGPL 3.0
 */

namespace KapitchiIdentity\Model;

interface AuthIdentityInterface {
    public function setIdentity($identity);
    public function getIdentity();
    public function setId($id);
    public function getId();
    public function setSessionId($sessionId);
    public function getSessionId();
    public function isEqual(AuthIdentityInterface $identity);
}