<?php 
class ContragentSync extends AppModel 
{
    public $useTable = false;
    
    function __construct($id = false, $table = null, $ds = null) {
        $this->set_other_db('db_doc');
        parent::__construct($id, $table, $ds);
    }                 
}	 