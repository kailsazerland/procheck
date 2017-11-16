<?php 
class Aunpaid extends AppModel
{
    var $validate = array(
        '	money' => array(
            'rule' => array('minLength', '1')
        ,'message' => 'поле "Сумма" должно быть заполнено'
        ),
    );
    public $belongsTo = array('User'=> array( 'fields' => array('username')),
        'Orgs'=> array( 'fields' => array('name')),
        'Contragent'=> array( 'fields' => array('name')),
        'Article'=> array( 'fields' => array('name')),
        'PayType'=> array( 'fields' => array('name')),
        'Classification'=> [
            'className' => 'Classificator',
            'foreignKey' => 'classification_id',
            'fields' => array('name')
        ],
        'Source'=> ['fields' => array('name')],
        );
        public $hasMany = array('AunpaidHistory' => ['order' => array('id' => 'DESC')]);

//    public function recount($id)
//    {
//        $order_cards = $this->Aunpaid->find('list',array('fields' => array('id','cash'),'conditions' => array('Aunpaid.order_id' => $id)));
//        $summ = array_reduce($order_cards, function ($v, $w) {$v += $w;return $v;});
//        $this->updateAll(array('cash' => $summ), array('Order.id' => $id));
//        return true;
//    }
}	 