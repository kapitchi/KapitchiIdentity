<?php
namespace KapitchiIdentity\View\Helper;

use KapitchiEntity\View\Helper\AbstractEntityHelper;

class Identity extends AbstractEntityHelper
{
    public function getDisplayName($id) {
        $identity = $this->getEntityService()->find($id);
        if(!$identity) {
            return $this->getView()->translate('N/A');
        }
        return $identity->getDisplayName();
    }
    
}