<?php

class ReportsController extends AppController {
    public $name = 'Reports';   
    public $layout = 'maina-layout';
    public $paginate = null;
    
    public function group_view()
    {
        $groups = $this->Report->ReportGroup->find('all');
        $this->set('groups',$groups);
        
        $this->ajaxRender();
    }     
    
    public function view($group_id = 0)
    {
        $this->set_action_menu( array(
            1 => array('name' => 'Выполнить','controller' => 'reports','action' => 'set_params','class' => 'btn-success dialog-link')
           ,2 => array('name' => 'Редактировать','icon' => 'fa fa-pencil','controller' => 'reports','action' => 'edit','class' => 'btn-primary')
           ,3 => array('name' => 'Удалить','icon' => 'fa fa-trash','controller' => 'reports','action' => 'delete','class' => 'btn-danger confirm')
        )); 
        
        $this->paginate['Report'] =  array('limit' => 12
                                            ,'conditions' => array('report_group_id' => $group_id)
                                            ,'order' => array('created' => 'DESC'));   
        
        $reports = $this->paginate('Report');
        $this->set('reports',$reports);
    
        $this->ajaxRender('view');
    }

    public function add()
    {
        $this->request->data = array('Report' => array(
            'id' => 0
           ,'ReportGroups' => $this->Report->ReportGroup->find('list')
        ));
        $this->ajaxRender('edit'); 
        $this->render('edit');
    }

    public function edit($id = 0)
    {
        $this->Report->id = $id;
        $data = $this->Report->read(); 
        $data['Report']['ReportGroups'] = $this->Report->ReportGroup->find('list');
        $this->request->data = $data;
        $this->ajaxRender();
    }    

    public function save()
    {
        if(isset($this->data['Report'])&&!isset($this->data['cancel']))
        {
            $params = array();
            preg_match_all('/:([^\[\]\(\),\s]*)\[([^\[]*)\]\[([^\[]*)\]\[([^\[\]]*)\]/im',$this->data['Report']['sql'],$data,PREG_SET_ORDER);             
            foreach ($data as $key => $value) {
                $params[] = array(
                    'name' => $value[1]
                   ,'label' => $value[2]
                   ,'value' => $value[3]                        
                   ,'pattern' => '/:([^\[\]\(\),\s]*)\[([^\[]*)\]\[([^\[]*)\]\[([^\[\]]*)\]/im'
                   ,'alias' => $value[4] 
                );
            }
            
            $this->request->data['Report']['params'] = serialize($params);

            if($this->Report->saveAll($this->data))
                $this->Session->setFlash('Запись сохранена.', 'default', array('class' => 'panel-success'));                
            else $this->Session->setFlash('Ошибка сохранения!', 'default', array('class' => 'panel-danger')); 
        }
        $this->redirect('view/' . $this->data['Report']['report_group_id']);
    }    

    public function delete($id = 0)
    {
        if($this->Report->delete($id))
            $this->Session->setFlash('Запись удалена.', 'default', array('class' => 'panel-success'));                
        else $this->Session->setFlash('Ошибка удаления!', 'default', array('class' => 'panel-danger'));         
        $this->redirect('group_view');
    }  
    
    public function set_params($id = 0)
    {
        $report = $this->Report->read(false,$id);
        $report['Report']['params'] = unserialize($report['Report']['params']);
        $this->request->data = $report;
        $this->ajaxRender();
    }
    
    public function make_report()
    {      
        $report = $this->Report->read(false,$this->data['Report']['id']);        
        $report['Report']['params'] = unserialize($report['Report']['params']);       
        $init_params = $this->data['Report']['params'];
        $patterns = $replacements = $patterns_alias = $replacements_alias = array();
        foreach ($report['Report']['params'] as $key => $param) {
            $patterns[] = $param['pattern'];
            switch ($param['name'])
            {
                case 'period':
                    $date_start = new DateTime($init_params[$key]['date_start']);            
                    $date_start = $date_start->format('Y-m-d 00:00:00');                    
                    $date_end = new DateTime($init_params[$key]['date_end']);            
                    $date_end = $date_end->format('Y-m-d 23:59:59');                       
                    $replacement = 'BETWEEN \'' . $date_start . '\' AND \'' . $date_end . '\'';
                    $this->set('period',$init_params[$key]['date_start'] . ' по ' . $init_params[$key]['date_end']);
                    
                break;
                case 'input':
                    $replacement = $init_params[$key]['input'];
                break;
                case 'sql':
                    if($init_params[$key]['sql']==0) $replacement = 'NULL';
                    else $replacement = $init_params[$key]['sql'];
                break;   
                default:
                    $replacement = '0';
                break;            
            }    
            
            if(!empty($param['alias']))
            {
                $replacements[] = $patterns_alias[] = ':' . $param['alias'];
                $replacements_alias[] = $replacement;
            } else {
                $replacements[] = $replacement;
            }                                 
        }

        $report['Report']['sql'] = preg_replace($patterns, $replacements, $report['Report']['sql'],1);
        foreach ($patterns_alias as $key => $pattern) {        
            $report['Report']['sql'] = preg_replace('/' . $pattern . '/im', $replacements_alias[$key], $report['Report']['sql']);       
        }
   
        try {
            $result = $this->Report->query($report['Report']['sql']);            
        } catch (Exception $exc) {
            //echo $exc->getTraceAsString();
            $this->Session->setFlash('Ошибка выполнения отчета!', 'default', array('class' => 'panel-danger'));
            $result = null;
        }

        //Нормализуем данные
        $tmp = array();
        foreach ($result as $k => $rec) 
        {
            foreach ($rec as $flds)
                $tmp = array_merge($tmp,$flds);
            $result[$k] = $tmp;
        }
        
        
        $this->set('report_group_id',$report['Report']['report_group_id']);
        $this->set('result',$result);
        $this->set('name',$report['Report']['name']);
        
        //$this->set('sql',$report['Report']['sql']);        

        if($this->data['Report']['type']=='table') {
            $this->ajaxRender('report_view');
            $this->render('report_view');
        } elseif($this->data['Report']['type']=='excel') {
            $this->layout = 'ajax';
            $this->autoRender = false;    
            //ob_end_clean();
            $this->ajaxRender('excel');
            $this->render('excel');     
        } elseif($this->data['Report']['type']=='pdf') {
            $this->layout = 'ajax';
            $this->autoRender = false;
            $this->set('pdf',true);
            $this->ajaxRender('pdf');
            $this->render('pdf');                 
        } elseif($this->data['Report']['type']=='chart') {
            $this->ajaxRender('chart_view');
            $this->render('chart_view');            
        }
    }

    public function beforeFilter()
    {

    }

}	 

?>
