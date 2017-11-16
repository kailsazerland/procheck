<?php
$this->Paginator->options(array('class' => 'ajax-link','url' => $this->passedArgs));
?>
    <script>
        if(!window.jQuery){
            document.write('<scr'+'ipt type="text/javascript" src="/lib/jquery/jquery.min.js"></scr'+'ipt>');
            document.write('<scr'+'ipt type="text/javascript" src="/lib/jquery-ui/jquery-ui.min.js"></scr'+'ipt>');
        }

    </script>
<div class="row">
    <div id="breadcrumb" class="col-xs-12">
        <ol class="breadcrumb">
            <li class="hidden-xs"><a href="#">К началу</a></li>
            <li><a href="#">Платежи</a></li>
            <li><a href="#">Новые</a></li>
        </ol>
    </div>
</div>
<?
//echo "<pre>";
//print_r($this);
//echo "</pre>";
?>

<div class="row">
    <div class="navbar-form">
        <div class="navbar-btn navbar-left">
            <?php echo $this->Html->link('Добавить','add',array('class' => 'btn btn-success ajax-link'));?>
        </div>
        <?php echo $this->element('search',array('search_view' => $search .'/'. current($pays)['Pay']['orgs_id'], 'class' => 'navbar-text')); ?>
    </div>
</div>
<?
//echo $this->Form->create('Pay', array('action' => '/pays/exp/', 'action' => '/pays/exp/', 'onsubmit' => 'return beforeSubmit(this);'));
echo $this->Form->create('Pay', array('url' => '/pays/exp', 'class' => 'form', 'role' => 'form'));
?>
<?if($arch != 1){?>
<div class="row">
    <div id="export" class="col-xs-12" style="margin-bottom: 20px;">
        <?php echo $this->Form->submit('Выгрузить выбранные в банк',array('class' => 'btn btn-success','div' => false));
        echo $this->Form->input('page',array('type' => 'hidden', 'value'=> $id)); ?>
    </div>
</div>
<?}?>
<table class='table table-hover table-striped table-bordered table-condensed'>
    <thead>
    <tr>
        <th><input onclick="checkAll(this)" type="checkbox"></th>
        <th><?php echo $this->Admin->PaginatorSort('date','Дата') ?></th>
        <th><?php echo $this->Admin->PaginatorSort('id','Номер') ?></th>
        <th>Организация</th>
        <th>Банк</th>
        <th>Сумма</th>
        <th>Контрагент</th>
        <th>Вид операции</th>
        <th>Статья</th>
        <th>Назначение платежа</th>
        <th>Бюджет</th>
        <th><?php echo $this->Admin->PaginatorSort('User.username','Автор') ?></th>
        <th style="width: 243px;">Управление</th>

    </tr>
    </thead>
    <?php
    // echo '<pre>';print_r($pays);echo "</pre>";
    foreach ($pays AS $key=>$value) {
        echo $this->Html->TableCells(array(
        ($value['Pay']['budget'] != 0) ? $this->Form->checkbox('modify][' . $value['Pay']['id'], array('value' => $value['Pay']['id'])) : ''
        , $this->Time->format('d.m.Y', $value['Pay']['date'])
        , $value['Pay']['id']
        , $value['Orgs']['name']
        , $value['orgBank']['bank']
        , number_format($value['Pay']['money'], 0, "", " ")
        , $value['Contragent']['name']
        , $value['PViews']['name']
        , $value['Article']['name']
        , $value['Pay']['point_payment']
        , ($value['Pay']['budget'] != 0) ? '<div class="text-center"><i class="fa fa-check fa-3 img-circle text-success"><i><div>' : ''
        , $value['User']['username']
        , array('<div class="btn-group">' . $this->ActionPanel->DrawPanel($action_menu, $value['Pay']['id']) . '</div>', array('class' => ''))
        ) , array('class' => ($value['Pay']['budg'] == 2) ?'danger':""));
    }
    ?>
</table>
<?
echo $this->Form->end();?>
<?php
if($this->Paginator->numbers()){
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
<?php } ?>
<?php echo $this->Html->scriptBlock('    
    function checkAll(theElement) {
        var z = 0;
         var inputCheck = $(\'.table-hover input[type="checkbox"]\');
        for(z=0; z<inputCheck.length;z++){
            if(inputCheck[z].type == "checkbox" && inputCheck[z].name != "checkall"){
                inputCheck[z].checked = theElement.checked;
            }
        }
    }
'); ?>
