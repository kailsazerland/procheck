<?php 
class TemplatePay extends AppModel 
{
    public $belongsTo = array('TemplateCalendar','Article','PayType','Org','Contragent');
}	 