<?php

namespace KapitchiIdentity\AuthStrategy;

use Zend\Authentication\Adapter,
    Zend\EventManager\ListenerAggregate;

interface Strategy extends Adapter, ListenerAggregate {
    
}