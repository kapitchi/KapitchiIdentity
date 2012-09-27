<?php
namespace KapitchiIdentity\Controller\Api;

use Zend\View\Model\JsonModel,
    KapitchiEntity\Controller\EntityRestfulController;

/**
 *
 * @author Matus Zeman <mz@kapitchi.com>
 */
class IdentityRestfulController extends EntityRestfulController
{
    public function autocompleteAction()
    {
        $service = $this->getEntityService();
        
        $value = $this->getRequest()->getQuery()->get('value');
        if($value) {
            $entity = $service->find($value);
            return new JsonModel(array(
                'label' => $entity->getDisplayName(),
                'value' => $entity->getDisplayName(),
                'id' => $entity->getId(),
            ));
        }
        
        $items = $service->getPaginator(array(
            //'displayName' => new 
        ));
        
        $ret = array();
        foreach($items as $r) {
            //$arr = $service->createArrayFromEntity($r);
            $arr = array(
                'id' => $r->getId(),
                'label' => $r->getDisplayName(),
                'value' => $r->getDisplayName(),
            );
            $ret[] = $arr;
        }
        
        $jsonModel = new JsonModel($ret);
        $this->getEventManager()->trigger('autocomplete', $this, array(
            'jsonViewModel' => $jsonModel
        ));
        return $jsonModel;
    }
}