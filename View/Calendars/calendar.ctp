<?php 
    $this->Paginator->options(array('class' => 'ajax-link','url' => $this->passedArgs));
?>
<div class="row">
        <div id="breadcrumb" class="col-xs-12">
                <ol class="breadcrumb">
                        <li class="hidden-xs"><a href="#">К началу</a></li>
                        <li><a href="#">Справочники</a></li>
                        <li><a href="#">Платежный календарь</a></li>                      
                        <li><a href="#"><?php echo $calendar['Calendar']['name']; ?></a></li>
                </ol>
        </div>
</div>


<div class="row">
    <div class="navbar-form">
        <div class="navbar-btn navbar-left">
            <?php echo $this->Html->link('<i class="fa fa-arrow-left"></i>&nbspНазад','view_calenars',array('class' => 'btn btn-primary ajax-link','escape' => false));?>
            <?php echo $this->Html->link('Добавить','pay_add/' . $calendar['Calendar']['id'],array('class' => 'btn btn-success ajax-link'));?>
        </div>    
    </div>
</div>  


<table class='table table-hover table-striped table-bordered table-condensed'>
    <thead>
        <tr>
            <th><?php echo $this->Admin->PaginatorSort('Article.name','Статья') ?></th>
            <th><?php echo $this->Admin->PaginatorSort('Org.name','Организация') ?></th>
            <th><?php echo $this->Admin->PaginatorSort('Contragent.name','Контрагент') ?></th>
            <th><?php echo $this->Admin->PaginatorSort('PayType.name','Способ оплаты') ?></th>
            <th><?php echo $this->Admin->PaginatorSort('cash','План(сумма)') ?></th>
            <th><?php echo $this->Admin->PaginatorSort('beg','Начало периода') ?></th>
            <th><?php echo $this->Admin->PaginatorSort('end','Конец периода') ?></th>
            <th>Управление</th>
        </tr>
    </thead>
    <?php
        foreach ($calendar_pays AS $value)
        {
            echo $this->Html->TableCells(array(
                 $value['Article']['name']
                ,$value['Org']['name']
                ,$value['Contragent']['name']
                ,$value['PayType']['name']
                ,$value['CalendarPay']['cash']
                ,$this->Time->format('d.m.Y',$value['CalendarPay']['beg'])
                ,$this->Time->format('d.m.Y',$value['CalendarPay']['end'])
                ,array('<div class="btn-group">' . $this->ActionPanel->DrawPanel($action_menu,$value['CalendarPay']['id']) . '</div>',array('class'=>''))
            ));
        }
    ?>    
</table>

<?php 
    if($this->Paginator->numbers()): 
?>
<?php 
   echo '<ul class="pagination pagination-sm">';
   echo $this->Paginator->prev('<span class="glyphicon glyphicon-step-backward"></span>', array('escape' => false, 'tag' => 'li'), null, array('escape' => false, 'tag' => 'li', 'class' => 'disabled', 'disabledTag' => 'a'));
   echo $this->Paginator->numbers(array(
       'currentClass' => 'active',
       'currentTag' => 'a',
       'tag' => 'li',
       'separator' => ''
   ));
   echo $this->Paginator->next('<span class="glyphicon glyphicon-step-forward"></span>', array('escape' => false, 'tag' => 'li', 'currentClass' => 'disabled'), null, array('escape' => false, 'tag' => 'li', 'class' => 'disabled', 'disabledTag' => 'a'));
   echo '  </ul>'; 
?> 
<?php endif; ?>

