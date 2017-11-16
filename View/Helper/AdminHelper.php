<?php

App::uses('Helper', 'View');

class AdminHelper extends Helper {
	public $helpers = array('Html','Paginator');
        
        public function PaginatorSort ($key,$title)
	{
            $ico ='<i class="fa fa-sort"></i>';
            if($this->Paginator->sortKey()==$key) {
               if($this->Paginator->sortDir()==='asc') $ico ='<i class="fa fa-sort-up"></i>';
                else $ico ='<i class="fa fa-sort-down"></i>';
            }
            $data = '<div class="sortable-col">';
            $data .= $this->Paginator->sort($key,$title,array('class' => 'ajax-link'));
            $data .= '&nbsp' . $ico;
            $data .= '</div>';
            return $data;
        }   
}


?>
