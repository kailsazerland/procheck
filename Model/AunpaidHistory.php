<?php 
class AunpaidHistory extends AppModel
{
    public $belongsTo = array('User'=> array( 'fields' => array('username')));
}	 