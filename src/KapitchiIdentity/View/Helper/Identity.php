<?php

namespace KapitchiIdentity\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Identity extends AbstractHelper
{
    public function __invoke()
    {
        var_dump('test');
        exit;
    }
}