<?php

class NomenclaturesController extends AppController {
    public $name = 'Nomenclatures';   
    public $layout = 'maina-layout';
    public $paginate = null;
    
    public function type_view()
    {
        $types = $this->Nomenclature->TypeNomenclature->find('all');
        $this->set('types',$types);
        
        $this->ajaxRender();
    }    
    
    public function view($type_id)
    {
        $this->set_action_menu( array(
            1 => array('name' => 'История','controller' => 'nomenclatures','action' => 'view_history','class' => 'btn-info')
           ,2 => array('name' => 'Редактировать','icon' => 'fa fa-pencil','controller' => 'nomenclatures','action' => 'edit','class' => 'btn-primary')
           ,3 => array('name' => 'Удалить','icon' => 'fa fa-trash','controller' => 'nomenclatures','action' => 'delete','class' => 'btn-danger confirm')
        )); 
        
        $this->paginate['Nomenclature'] =  array('limit' => 30
                                            ,'conditions' => array_merge(array('type_nomenclature_id' => $type_id),$this->search_conditions)
                                            ,'order' => array('created' => 'DESC'));   
        
        $nomenclatures = $this->paginate('Nomenclature');
        $this->set('nomenclatures',$nomenclatures);
    
        $this->ajaxRender('view');
    }

    public function add()
    {
        $this->request->data = array('Nomenclature' => array('id' => 0
            ,'TypeNomenclatures' => $this->Nomenclature->TypeNomenclature->find('list')
            ,'Articles' => $this->Nomenclature->Article->get_list()
        ));
        $this->ajaxRender('edit'); 
        $this->render('edit');
    }    

    public function edit($id = 0)
    {
        $this->Nomenclature->id = $id;
        $data = $this->Nomenclature->read(); 
        $data['Nomenclature']['TypeNomenclatures'] = $this->Nomenclature->TypeNomenclature->find('list');
        $data['Nomenclature']['Articles'] = $this->Nomenclature->Article->get_list();
        $this->request->data = $data;
        $this->ajaxRender();
    }    
    
    public function change()
    {
        $this->request->data = $this->data;
        $this->request->data['Nomenclature']['TypeNomenclatures'] = $this->Nomenclature->TypeNomenclature->find('list');
        $this->ajaxRender('edit'); 
        $this->render('edit');
    }      

    public function save()
    {
        if(isset($this->data['Nomenclature'])&&!isset($this->data['cancel']))
        {
            if($this->Nomenclature->saveAll($this->data)) {
                
                $id = $this->data['Nomenclature']['id'];
                if($id == 0) $id = $this->Nomenclature->getLastInsertid();
                
                $data = array(
                    'id' => 0
                   ,'nomenclature_id' => $id
                   ,'price' => $this->data['Nomenclature']['price']
                   ,'period' => date('Y-m-d')
                );            
                $this->Nomenclature->NomenclatureHistory->save($data);                
                
                $this->Session->setFlash('Запись сохранена.', 'default', array('class' => 'panel-success'));                
            }
            else $this->Session->setFlash('Ошибка сохранения!', 'default', array('class' => 'panel-danger')); 
        }
        $this->redirect('view/' . $this->data['Nomenclature']['type_nomenclature_id']);
    }    

    public function delete($id = 0)
    {
        if($this->Nomenclature->delete($id))
            $this->Session->setFlash('Запись удалена.', 'default', array('class' => 'panel-success'));                
        else $this->Session->setFlash('Ошибка удаления!', 'default', array('class' => 'panel-danger'));         
        $this->redirect('type_view');
    }    

    public function beforeFilter()
    {

    }
    
    public function get_search_conditions($search_text = null) {
        $type_nomenclatures = $this->Nomenclature->TypeNomenclature->find('list',array('fields' => array('id') ,'conditions' => array('TypeNomenclature.name LIKE "%' . $search_text . '%"')));
        $conditions = array(
            'OR' => array(
                'Nomenclature.name LIKE "%' . $search_text . '%"'
               ,'Nomenclature.type_nomenclature_id' => $type_nomenclatures 
               ,'Nomenclature.price LIKE "%' . $search_text . '%"'
            )
        );
        return $conditions;
    }  
    
    public function view_history($id = 0)
    {
        $this->Nomenclature->id = $id;
        $data = $this->Nomenclature->read(); 
        $this->request->data = $data;
        $this->ajaxRender();
    }
}	 

?>
