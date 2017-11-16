<?php

class TemplateCalendarsController extends AppController {
    public $name = 'TemplateCalendars';   
    public $layout = 'maina-layout';
    public $paginate = null;
    
    public function view()
    {
        $this->set_action_menu( array(
            1 => array('name' => 'Шаблон','controller' => 'template_calendars','action' => 'template_pay_view','class' => 'btn-info')            
           ,2 => array('name' => 'Редактировать','icon' => 'fa fa-pencil','controller' => 'template_calendars','action' => 'edit','class' => 'btn-primary')
           ,3 => array('name' => 'Удалить','icon' => 'fa fa-trash','controller' => 'template_calendars','action' => 'delete','class' => 'btn-danger confirm')
        )); 
        
        $this->paginate['TemplateCalendar'] =  array('limit' => 30
                                            ,'order' => array('created' => 'DESC'));   
        
        $templatecalendars = $this->paginate('TemplateCalendar');
        $this->set('templatecalendars',$templatecalendars);
    
        $this->ajaxRender('view');
    }

    public function add()
    {
        $this->request->data = array('TemplateCalendar' => array('id' => 0));
        $this->ajaxRender('edit'); 
        $this->render('edit');
    }    

    public function edit($id = 0)
    {
        $this->TemplateCalendar->id = $id;
        $data = $this->TemplateCalendar->read(); 
        $this->request->data = $data;
        $this->ajaxRender();
    }    

    public function save()
    {
        if(isset($this->data['TemplateCalendar'])&&!isset($this->data['cancel']))
        {      
            if($this->TemplateCalendar->saveAll($this->data))
                $this->Session->setFlash('Запись сохранена.', 'default', array('class' => 'panel-success'));                
            else $this->Session->setFlash('Ошибка сохранения!', 'default', array('class' => 'panel-danger')); 
        }
        $this->redirect('view');
    }    

    public function delete($id = 0)
    {
        if($this->TemplateCalendar->delete($id))
            $this->Session->setFlash('Запись удалена.', 'default', array('class' => 'panel-success'));                
        else $this->Session->setFlash('Ошибка удаления!', 'default', array('class' => 'panel-danger'));         
        $this->redirect('view');
    }
    
    public function template_pay_view($template_id = 0)
    {
        $this->set_action_menu( array(
            1 => array('name' => 'Редактировать','icon' => 'fa fa-pencil','controller' => 'template_calendars','action' => 'template_pay_edit','class' => 'btn-primary')
           ,2 => array('name' => 'Удалить','icon' => 'fa fa-trash','controller' => 'template_calendars','action' => 'template_pay_delete','class' => 'btn-danger confirm')
        )); 
                
        $this->TemplateCalendar->id = $template_id;
        $data = $this->TemplateCalendar->read(); 
        $this->set('template_calendar',$data);
        
        $this->loadModel('TemplatePay');
        $this->paginate['TemplatePay'] =  array('limit' => 30
                                            ,'conditions' => array('template_calendar_id' => $template_id)
                                            ,'order' => array('created' => 'DESC'));   
        $templatepays = $this->paginate('TemplatePay');
        $this->set('templatepays',$templatepays);
    
        $this->ajaxRender('template_pay_view');       
    }
    
    public function template_pay_add($template_id = 0)
    {
        $this->TemplateCalendar->id = $template_id;
        $data = $this->TemplateCalendar->read(); 
        $this->set('template_calendar',$data);
        
        $this->request->data = array('TemplatePay' => array('id' => 0
            ,'template_calendar_id' => $template_id
            ,'Articles' => $this->TemplateCalendar->TemplatePay->Article->get_list(array('conditions' => array('type_id' => $this->TemplateCalendar->TemplatePay->Article->Type->get_id_out())))
            ,'Orgs' => $this->TemplateCalendar->TemplatePay->Org->find('list',array('conditions' => array('orgs_group_id' => 99)))
            ,'Contragents' => $this->TemplateCalendar->TemplatePay->Contragent->get_list(
                array('conditions' => 
                    array('orgs_group_id' => $this->TemplateCalendar->TemplatePay->Contragent->OrgsGroup->find('list',array('fields' => array('id','id'),'conditions' => array('type_id' => $this->TemplateCalendar->TemplatePay->Article->Type->get_id_out()))))))
            ,'PayTypes' => $this->TemplateCalendar->TemplatePay->PayType->find('list')
        ));
        
        $this->ajaxRender('template_pay_edit'); 
        $this->render('template_pay_edit');
    }
    
    public function template_pay_edit($id = 0)
    {
        $this->TemplateCalendar->TemplatePay->id = $id;
        $data = $this->TemplateCalendar->TemplatePay->read(); 
        $data['TemplatePay']['Articles'] = $this->TemplateCalendar->TemplatePay->Article->get_list(array('conditions' => array('type_id' => $this->TemplateCalendar->TemplatePay->Article->Type->get_id_out())));  
        
        $data['TemplatePay']['Orgs'] = $this->TemplateCalendar->TemplatePay->Org->find('list',array('conditions' => array('orgs_group_id' => 99)));
        $data['TemplatePay']['Contragents'] = $this->TemplateCalendar->TemplatePay->Contragent->get_list(
                array('conditions' => 
                    array('orgs_group_id' => $this->TemplateCalendar->TemplatePay->Contragent->OrgsGroup->find('list',array('fields' => array('id','id'),'conditions' => array('type_id' => $this->TemplateCalendar->TemplatePay->Article->Type->get_id_out()))))));
        $data['TemplatePay']['PayTypes'] = $this->TemplateCalendar->TemplatePay->PayType->find('list');
        
        $date = new DateTime($data['TemplatePay']['beg']);            
        $data['TemplatePay']['beg'] = $date->format('d.m.Y');  
        
        $date = new DateTime($data['TemplatePay']['end']);            
        $data['TemplatePay']['end'] = $date->format('d.m.Y');          
            
        $this->request->data = $data;    
        
        $this->TemplateCalendar->id = $data['TemplatePay']['template_calendar_id'];
        $data = $this->TemplateCalendar->read(); 
        $this->set('template_calendar',$data);
        
        $this->ajaxRender(); 
    }  

    public function template_pay_save()
    {
        if(isset($this->data['TemplatePay'])&&!isset($this->data['cancel']))
        {
            if(isset($this->data['TemplatePay']['beg'])&&isset($this->data['TemplatePay']['end'])) {
                $date = new DateTime($this->data['TemplatePay']['beg']);            
                $this->request->data['TemplatePay']['beg'] = $date->format('Y-m-d 00:00:00');
                $date = new DateTime($this->data['TemplatePay']['end']);            
                $this->request->data['TemplatePay']['end'] = $date->format('Y-m-d 00:00:00');            
            }
            
            if($this->TemplateCalendar->TemplatePay->saveAll($this->data))
                $this->Session->setFlash('Запись сохранена.', 'default', array('class' => 'panel-success'));                
            else $this->Session->setFlash('Ошибка сохранения!', 'default', array('class' => 'panel-danger')); 
        }
        $this->redirect('template_pay_view/' . $this->data['TemplatePay']['template_calendar_id']);
    } 
    
    public function template_pay_delete($id = 0)
    {
        $this->TemplateCalendar->TemplatePay->id = $id;
        $data = $this->TemplateCalendar->TemplatePay->read();         
        if($this->TemplateCalendar->TemplatePay->delete($id))
            $this->Session->setFlash('Запись удалена.', 'default', array('class' => 'panel-success'));                
        else $this->Session->setFlash('Ошибка удаления!', 'default', array('class' => 'panel-danger'));         
        $this->redirect('template_pay_view/' . $data['TemplatePay']['template_calendar_id']);
    }
    
    public function beforeFilter()
    {

    }
       

}	 

?>
