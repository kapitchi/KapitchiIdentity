<?php
namespace KapitchiIdentity\Form;

use KapitchiBase\InputFilter\EventManagerAwareInputFilter;
/**
 *
 * @author Matus Zeman <mz@kapitchi.com>
 */
class LoginInputFilter extends EventManagerAwareInputFilter
{
    public function __construct()
    {
        $this->setValidationGroup(array());
    }
}