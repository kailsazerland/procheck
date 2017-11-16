<?php 
class Order extends AppModel 
{
    public $belongsTo = array('User','Otdel');
    public $hasMany = array('OrderCard');
    
    public function recount($id)
    {
        $order_cards = $this->OrderCard->find('list',array('fields' => array('id','cash'),'conditions' => array('OrderCard.order_id' => $id)));
        $summ = array_reduce($order_cards, function ($v, $w) {$v += $w;return $v;});
        $this->updateAll(array('cash' => $summ), array('Order.id' => $id));
        return true;
    }
}