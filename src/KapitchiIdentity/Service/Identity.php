<?php
/**
 * Kapitchi Zend Framework 2 Modules (http://kapitchi.com/)
 *
 * @copyright Copyright (c) 2012-2013 Kapitchi Open Source Team (http://kapitchi.com/open-source-team)
 * @license   http://opensource.org/licenses/LGPL-3.0 LGPL 3.0
 */

namespace KapitchiIdentity\Service;

use KapitchiEntity\Service\EntityService;

class Identity extends EntityService
{
    protected function attachDefaultListeners()
    {
        parent::attachDefaultListeners();
        $this->getEventManager()->attach('persist', function($e) {
            $entity = $e->getEntity();
            if(!$entity->getId()) {
                //new entity
                if($entity->getCreated() === null) {
                    $entity->setCreated(new \DateTime());
                }
                if($entity->getAuthEnabled() === null) {
                    $entity->setAuthEnabled(false);
                }
            }
        }, 2);
    }
}