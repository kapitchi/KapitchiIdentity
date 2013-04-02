<?php
namespace KapitchiIdentity\Model;

/**
 *
 * @author Matus Zeman <mz@kapitchi.com>
 */
class AuthIdentityContainerHydrator implements \Zend\Stdlib\Hydrator\HydratorInterface
{
    protected $authIdentityHydrator;
    
    /**
     * 
     */
    public function hydrate(array $data, $object)
    {
        $hydrator = $this->getAuthIdentityHydrator();
        
        if(isset($data['identities']) && is_array($data['identities'])) {
            foreach($data['identities'] as $id) {
                $identity = new AuthIdentity();
                $object->addIdentity($hydrator->hydrate($id, $identity));
            }
        }
        
        if(isset($data['defaultIdentity'])) {
            $identity = new AuthIdentity();
            $object->setDefaultIdentity($hydrator->hydrate($data['defaultIdentity'], $identity));
        }
        
        return $object;
    }
    
    /**
     * 
     * @param AuthIdentityContainer $object
     */
    public function extract($object)
    {
        $data = array();
        
        $ids = array();
        $hydrator = $this->getAuthIdentityHydrator();
        foreach($object->getIdentities() as $identity) {
            $ids[] = $hydrator->extract($identity);
        }
        $data['identities'] = $ids;
        
        $defaultData = null;
        $default = $object->getDefaultIdentity();
        if($default) {
             $defaultData = $hydrator->extract($default);
        }
        $data['defaultIdentity'] = $defaultData;
        
        return $data;
    }

    /**
     * @return AuthIdentityHydrator
     */
    public function getAuthIdentityHydrator()
    {
        return $this->authIdentityHydrator;
    }

    public function setAuthIdentityHydrator($authIdentityHydrator)
    {
        $this->authIdentityHydrator = $authIdentityHydrator;
    }
}