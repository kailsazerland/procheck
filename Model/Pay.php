<?php 
class Pay extends AppModel
{
    var $validate = array(
        '	money' => array(
            'rule' => array('minLength', '1')
        ,'message' => 'поле "Сумма" должно быть заполнено'
        ),
        'orgs_id' => array(
            'rule' => array('minLength', '1')
        ,'message' => 'поле "Организация" должно быть заполнено'
        ),
    );
    public $belongsTo = array('User'=> array( 'fields' => array('username')),
        'Orgs',
//        'Reports'=> array( 'fields' => array('id','name')),
        'Contragent',
        'Account' => array('foreignKey' => 'bank_id'),
        'orgBank' => array('className' => 'Account','foreignKey' => 'accounts_id'),
        'Nalog',
        'PPaymentType',
        'PPriorities',
        'PViews'=> array( 'fields' => array('name','id')),
        'Article',
        );
    public $hasMany = array('PayHistory' => ['order' => array('id' => 'DESC')]);
}	 