<?php
/**
 * Kapitchi Zend Framework 2 Modules (http://kapitchi.com/)
 *
 * @copyright Copyright (c) 2012-2013 Kapitchi Open Source Team (http://kapitchi.com/open-source-team)
 * @license   http://opensource.org/licenses/LGPL-3.0 LGPL 3.0
 */

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
        
        $query = $this->getRequest()->getQuery();
        $value = $query->get('value');
        if($value) {
            $entity = $service->find($value);
            return new JsonModel(array(
                'label' => $entity->getDisplayName(),
                'value' => $entity->getDisplayName(),
                'id' => $entity->getId(),
            ));
        }
        
        $items = $service->getPaginator(array(
            'fulltext' => $query->get('term')
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
    
    protected function attachDefaultListeners()
    {
        parent::attachDefaultListeners();
        
        $em = $this->getEventManager();
        $instance = $this;
        
        $em->attach('get.post', function($e) {
            if($e->getTarget()->getRequest()->getQuery()->get('context') == 'entity-lookup-input') {
                $model = $e->getParam('jsonViewModel');
                $entity = $model->getVariable('entity');
                $label = $entity['displayName'];
                
                $e->getParam('jsonViewModel')->label = $label;
            }
        });
    }
}