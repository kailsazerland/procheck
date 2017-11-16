<?php 
class OrgsGroup extends AppModel 
{
    public $belongsTo = array('Type');
    public $hasMany = array('Contragent');
}