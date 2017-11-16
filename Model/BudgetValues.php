<?php 
class BudgetValues extends AppModel 
{
    public $useTable = 'budgets';
    public $belongsTo = array('Budget' => array('foreignKey' => 'parent_id'),'Article','Type');

    
}	 