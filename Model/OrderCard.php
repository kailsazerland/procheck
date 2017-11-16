<?php 
class OrderCard extends AppModel 
{
    public $field_list = array(
       //'id' => 'Уникальный идентификатор'    
        'Article.name' => 'Статья'
       ,'Contragent.name' => 'Контрагент'
       ,'cash' => 'Сумма руб.'
       ,'Nalog.name' => 'НДС'
       ,'PayType.name' => 'Способ оплаты'
       ,'about' => 'Описание'
    );    
    var $validate = array(
        'about' => array(
            'rule' => array('minLength', '1')
           ,'message' => 'поле "Описание" должно быть заполнено'
        ),
    );
    public $belongsTo = array('Order','Article','PayType','Nalog','Contragent');
}	 