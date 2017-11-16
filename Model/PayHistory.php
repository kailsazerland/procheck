<?php 
class PayHistory extends AppModel
{
    public $belongsTo = array('User'=> array( 'fields' => array('username')));
}	 