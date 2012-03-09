<?php

namespace KapitchiIdentity\Service;

use Zend\Form\Form;

class IdentityForm extends Identity {
    
    public function saveFromForm(Form $form) {
        $id = $form->getValue('id');
        $data = $form->getValues();
        
        $params = array(
            'form' => $form,
            'data' => $data,
        );
        $this->events()->trigger('saveFromForm.pre', $this, $params);
        
        if($id !== null) {
            //update
            $ret = $this->update($id, $data);
        }
        else {
            //create
            $ret = $this->create($data);
        }
        
        $this->events()->trigger('saveFromForm.post', $this, $params);
        
        return $ret;
    }
    
}