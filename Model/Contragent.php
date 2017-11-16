<?php 
class Contragent extends AppModel 
{
    public $useTable = 'orgs';
    public $belongsTo = array('OrgsGroup'/*,'Type'*/);
    public $hasMany = array('Account' => array('foreignKey' => 'org_id'));
    
    public function find($type = 'first', $query = array()) {
        $result = parent::find($type, $query);
        //if($type == 'list') $result = array_merge(array(0 => 'нет'),$result);
        return $result;
    }              
}	 