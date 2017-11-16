<?php 
class Type extends AppModel 
{
    //public  $hasMany = array('Article');
    
    public function get_id_in() {//доход
        $data = $this->find('first',array('conditions' => array('alias' => 'in')));
        return $data['Type']['id'];
    }
    
    public function get_id_out() {//расход
        $data = $this->find('first',array('conditions' => array('alias' => 'out')));
        return $data['Type']['id'];
    }    
    
    public function is_in($conditions = null) {
        if($conditions == $this->get_id_in())
            return true;
        else return false;
    } 
    
    public function is_out($conditions = null) {
        if($conditions == $this->get_id_out())
            return true;
        else return false;
    }     
    
    public function find($type = 'first', $query = array()) {
        $result = parent::find($type, $query);
        //if($type == 'list') $result = array_merge(array(0 => 'нет'),$result);
        return $result;
    }            
}	 