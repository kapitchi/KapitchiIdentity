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
                $identity = new GenericAuthIdentity();
                $object->add($hydrator->hydrate($id, $identity));
            }
        }
        
        if(isset($data['defaultSessionId'])) {
            $object->setDefaultSessionId($data['defaultSessionId']);
        }
        
        if(isset($data['currentSessionId'])) {
            $object->setCurrentSessionId($data['currentSessionId']);
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
        $data['defaultSessionId'] = $object->getDefaultSessionId();
        $data['currentSessionId'] = $object->getCurrentSessionId();
        
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