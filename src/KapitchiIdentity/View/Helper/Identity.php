<?php

namespace KapitchiIdentity\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Identity extends AbstractHelper
{
    public function __invoke()
    {
        throw new \Exception('N/I');
    }
}