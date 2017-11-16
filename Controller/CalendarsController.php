<?php

class CalendarsController extends AppController {
    public $name = 'Calendars'; 
    public $layout = 'maina-layout';     
    public $paginate = null;

    public function view_calenars()
    {
        $this->set_action_menu( array(
            1 => array('name' => 'Просмотр','controller' => 'calendars','action' => 'calendar','class' => 'btn-primary')
           ,2 => array('name' => 'Диаграмма','controller' => 'calendars','action' => 'gant_diagram','class' => 'btn-primary')            
           ,3 => array('name' => 'Удалить','icon' => 'fa fa-trash','controller' => 'calendars','action' => 'delete','class' => 'btn-danger confirm')
        )); 
        
        $this->paginate['Calendar'] =  array('limit' => 12
                                            ,'conditions' => $this->search_conditions
                                            ,'order' => array('created' => 'DESC'));   
        $calenars = $this->paginate('Calendar');
       
        $this->set('calenars',$calenars);
    
        $this->ajaxRender();
    }
    
    public function preview_make()
    {
        $this->loadModel('TemplateCalendar');
        $templates = $this->TemplateCalendar->find('list');    
        $this->set('templates',$templates);    
        $this->ajaxRender();
    }
    
    public function calendar($id = 0)
    {
        $this->set_action_menu( array(
            1 => array('name' => 'Редактировать','icon' => 'fa fa-pencil','controller' => 'calendars','action' => 'pay_edit','class' => 'btn-primary')
           ,2 => array('name' => 'Удалить','icon' => 'fa fa-trash','controller' => 'calendars','action' => 'pay_delete','class' => 'btn-danger confirm')
        ));         
        
        $this->Calendar->id = $id;
        $data = $this->Calendar->read();
        $this->set('calendar',$data);
        
        $this->loadModel('CalendarPay');
        $this->paginate['CalendarPay'] = array('limit' => 30
                                            ,'conditions' => array('calendar_id' => $id)
                                            ,'order' => array('created' => 'DESC'));   
        $calendar_pays = $this->paginate('CalendarPay');
        $this->set('calendar_pays',$calendar_pays);        
        
        $this->ajaxRender();
    }
    
    public function pay_add($calendar_id = 0)
    {
        $this->Calendar->id = $calendar_id;
        $data = $this->Calendar->read(); 
        $this->set('calendar',$data);
        
        $this->request->data = array('CalendarPay' => array('id' => 0
            ,'calendar_id' => $calendar_id
            ,'Articles' => $this->Calendar->CalendarPay->Article->get_list(array('conditions' => array('type_id' => $this->Calendar->CalendarPay->Article->Type->get_id_out())))
            ,'Orgs' => $this->Calendar->CalendarPay->Org->find('list',array('conditions' => array('orgs_group_id' => 99)))
            ,'Contragents' => $this->Calendar->CalendarPay->Contragent->get_list(
                array('conditions' => 
                    array('orgs_group_id' => $this->Calendar->CalendarPay->Contragent->OrgsGroup->find('list',array('fields' => array('id','id'),'conditions' => array('type_id' => $this->Calendar->CalendarPay->Article->Type->get_id_out()))))))
            ,'PayTypes' => $this->Calendar->CalendarPay->PayType->find('list')
        ));
        
        $this->ajaxRender('pay_edit'); 
        $this->render('pay_edit');
    }
    
    public function pay_edit($id = 0)
    {
        $this->Calendar->CalendarPay->id = $id;
        $data = $this->Calendar->CalendarPay->read(); 
        $data['CalendarPay']['Articles'] = $this->Calendar->CalendarPay->Article->get_list(array('conditions' => array('type_id' => $this->Calendar->CalendarPay->Article->Type->get_id_out())));  
        
        $data['CalendarPay']['Orgs'] = $this->Calendar->CalendarPay->Org->find('list',array('conditions' => array('orgs_group_id' => 99)));
        $data['CalendarPay']['Contragents'] = $this->Calendar->CalendarPay->Contragent->get_list(
                array('conditions' => 
                    array('orgs_group_id' => $this->Calendar->CalendarPay->Contragent->OrgsGroup->find('list',array('fields' => array('id','id'),'conditions' => array('type_id' => $this->Calendar->CalendarPay->Article->Type->get_id_out()))))));
        $data['CalendarPay']['PayTypes'] = $this->Calendar->CalendarPay->PayType->find('list');
        
        $date = new DateTime($data['CalendarPay']['beg']);            
        $data['CalendarPay']['beg'] = $date->format('d.m.Y');  
        
        $date = new DateTime($data['CalendarPay']['end']);            
        $data['CalendarPay']['end'] = $date->format('d.m.Y');          
            
        $this->request->data = $data;    
        
        $this->Calendar->id = $data['CalendarPay']['calendar_id'];
        $data = $this->Calendar->read(); 
        $this->set('calendar',$data);
        
        $this->ajaxRender(); 
    }  

    public function pay_save()
    {
        if(isset($this->data['CalendarPay'])&&!isset($this->data['cancel']))
        {
            if(isset($this->data['CalendarPay']['beg'])&&isset($this->data['CalendarPay']['end'])) {
                $date = new DateTime($this->data['CalendarPay']['beg']);            
                $this->request->data['CalendarPay']['beg'] = $date->format('Y-m-d 00:00:00');
                $date = new DateTime($this->data['CalendarPay']['end']);            
                $this->request->data['CalendarPay']['end'] = $date->format('Y-m-d 00:00:00');            
            }
            
            if($this->Calendar->CalendarPay->saveAll($this->data))
                $this->Session->setFlash('Запись сохранена.', 'default', array('class' => 'panel-success'));                
            else $this->Session->setFlash('Ошибка сохранения!', 'default', array('class' => 'panel-danger')); 
        }
        $this->redirect('calendar/' . $this->data['CalendarPay']['calendar_id']);
    } 
    
    public function pay_delete($id = 0)
    {
        $this->Calendar->CalendarPay->id = $id;
        $data = $this->Calendar->CalendarPay->read();         
        if($this->Calendar->CalendarPay->delete($id))
            $this->Session->setFlash('Запись удалена.', 'default', array('class' => 'panel-success'));                
        else $this->Session->setFlash('Ошибка удаления!', 'default', array('class' => 'panel-danger'));         
        $this->redirect('calendar/' . $data['CalendarPay']['calendar_id']);
    }
    
    public function make()
    {
        if(isset($this->data['Calendar'])&&!isset($this->data['cancel']))
        {
            
            $period = new DateTime($this->data['Calendar']['period']);   

            $data = $this->data;     
            
            $data['Calendar']['period'] = $period->format('Y-m-01 00:00:00');
                                  
            $this->loadModel('TemplateCalendar');
            $template = $this->TemplateCalendar->read(null,$this->data['Calendar']['template_calendar_id']);                        
            foreach ($template['TemplatePay'] as $value) {
                unset($value['id']);   
                $date_beg = new DateTime($value['beg']);            
                $date_end = new DateTime($value['end']);                          
                $value['beg'] = new DateTime($period->format('Y-m-' . $date_beg->format('d') . ' 00:00:00'));
                $value['end'] = new DateTime($period->format('Y-m-' . $date_end->format('d') . ' 00:00:00'));    
                $value['beg'] = $value['beg']->format('Y-m-d 00:00:00');
                $value['end'] = $value['end']->format('Y-m-d 00:00:00');                  
                $data['CalendarPay'][] = $value;
            }   
        
            if($this->Calendar->saveAll($data)) {
                $this->Session->setFlash('Календарь сформирован.', 'default', array('class' => 'panel-success'));                
            }
            else $this->Session->setFlash('Ошибка формирования!', 'default', array('class' => 'panel-danger')); 
        }
        $this->redirect('view_calenars');
    }
    
    public function gant_diagram($id = 0)
    {
        App::uses('TimeHelper', 'View/Helper');
        $helper_time = new TimeHelper(new View());  
                
        $calendar = $this->Calendar->read(false,$id);
        $this->set('calendar',$calendar);
        
        $data = array();
        
        /*$data[] = array(
              'label' => 'Test',
              'info' => '',
              'start' => '2012-04-20', 
              'end'   => '2012-04-27',
              'class'   => 'urgent'
            ); 
        $data[] = array(
              'label' => 'Test',
              'info' => '<i class="fa fa-rub text-danger"></i> 1001 р./ план 2000',
              'start' => '2012-04-20', 
              'end'   => '2012-04-25'
            ); */
        
        $calendar_pay = $this->Calendar->CalendarPay->find('all',array('conditions' => array('calendar_id' => $id)));        
        foreach ($calendar_pay as $count => $value) {
            $data[] = array(
              'label' => $count+1 . '.' . $value['Article']['name'] . ' / ',
              'info' => $value['Contragent']['name'] . '(' . $value['CalendarPay']['cash'] . 'р.)',
              'start' => $helper_time->format('Y-m-d',$value['CalendarPay']['beg']), 
              'end'   => $helper_time->format('Y-m-d',$value['CalendarPay']['end']),
              'org' => ''//$value['Contragent']['name']
            );            
        }                      
       
        $this->set('data',$data);        
        $this->ajaxRender();
    }

    public function delete($id = 0)
    {
        if($this->Calendar->delete($id))
            $this->Session->setFlash('Запись удалена.', 'default', array('class' => 'panel-success'));                
        else $this->Session->setFlash('Ошибка удаления!', 'default', array('class' => 'panel-danger'));         
        $this->redirect('view_calenars');
    }    

}
