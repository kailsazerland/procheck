<?php 
class Nomenclature extends AppModel 
{
    public $belongsTo = array('TypeNomenclature','Article');
    public $hasMany = array('NomenclatureHistory');
    
    public function find($type = 'first', $query = array()) {
        $result = parent::find($type, $query);
        //if($type == 'list') $result = array_merge(array(0 => 'нет'),$result);
        return $result;
    }        
}	 