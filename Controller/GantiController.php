<?php

class GantiController extends AppController {
    public $name = 'Ganti';   
    public $layout = 'maina-layout';
    public $helpers = array('Ganti');
    
    public function filter()
    {
        $this->loadModel('TemplateCalendar');
        $templates = $this->TemplateCalendar->find('list');    
        $this->set('templates',$templates);        
        $this->ajaxRender();
    }
    
    public function view()
    {
        App::uses('TimeHelper', 'View/Helper');
        $helper_time = new TimeHelper(new View());                 

        $this->loadModel('CashFlow');  
        $this->loadModel('Article'); 
        
        $this->loadModel('TemplateCalendar');
        $template_calendar = $this->TemplateCalendar->find('first',array('conditions' => array('id' => $this->data['Filter']['template_id']))); 
        
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
        
        
        foreach ($template_calendar['TemplatePay'] as $value) {
            
            $article = $this->Article->find('first',array('conditions' => array('Article.id' => $value['article_id']))); 
            
            $data[] = array(
              'label' => $article['Article']['name'],
              'info' => $this->CashFlow->get_summ_to_article($value['article_id'],array('beg' => $value['beg'],'end' => $value['end'])),
              'start' => $helper_time->format('Y-m-d',$this->CashFlow->get_mindate_to_article($value['article_id'],$value['beg'],$value['end'])), 
              'end'   => $helper_time->format('Y-m-d',$this->CashFlow->get_maxdate_to_article($value['article_id'],$value['beg'],$value['end'])),
              'org' => ''//$value['Contragent']['name']
            );            
        }                      
      
        
        $this->set('type',$this->data['Filter']['type']);
        
        $this->set('data',$data);
        $this->ajaxRender();
    }
    
    public function beforeFilter()
    {

    }

}	 

?>
