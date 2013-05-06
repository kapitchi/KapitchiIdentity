<?php
/**
 * Kapitchi Zend Framework 2 Modules (http://kapitchi.com/)
 *
 * @copyright Copyright (c) 2012-2013 Kapitchi Open Source Team (http://kapitchi.com/open-source-team)
 * @license   http://opensource.org/licenses/LGPL-3.0 LGPL 3.0
 */

namespace KapitchiIdentity\View\Helper;

use KapitchiEntity\View\Helper\AbstractEntityHelper;

class Identity extends AbstractEntityHelper
{
    public function getDisplayName($id) {
        $identity = $this->getEntityService()->find($id);
        if(!$identity) {
            return null;
        }
        return $identity->getDisplayName();
    }
    
}