<?php 
class CalendarPay extends AppModel 
{
    public $belongsTo = array('Calendar','Article','PayType','Org','Contragent');
}	 