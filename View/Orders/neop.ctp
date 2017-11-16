<?php 
    $this->Paginator->options(array('class' => 'ajax-link','url' => $this->passedArgs));
?>
<div class="row">
        <div id="breadcrumb" class="col-xs-12">
                <ol class="breadcrumb">
                        <li class="hidden-xs"><a href="#">К началу</a></li>
                        <li><a href="#">Заявки</a></li>
                </ol>
        </div>
</div>

<div class="row">
    <div class="navbar-form">
        <div class="navbar-btn navbar-left">
            <?php echo $this->Html->link('Добавить','add_neop',array('class' => 'btn btn-success ajax-link'));?>
        </div>    
        <?php echo $this->element('search',array('search_view' => 'neop', 'class' => 'navbar-text')); ?> 
    </div>
</div>   
<table class='table table-hover table-striped table-bordered table-condensed'>
    <thead>
        <tr>
            <th><?php echo $this->Admin->PaginatorSort('created','Дата') ?></th>
            <th><?php echo $this->Admin->PaginatorSort('cash','Сумма') ?></th>
            <th>Организация</th>
            <th>Контрагент</th>
            <th>Способ оплаты</th>
            <th>Статья</th>
            <th>Классификация</th>
            <th>Источник</th>
            <th>Срок оплаты</th>
            <th>Примечание</th>
            <th>Файл</th>
            <th><?php echo $this->Admin->PaginatorSort('User.username','Автор') ?></th>
            <th><?php echo $this->Admin->PaginatorSort('is_confrim','Утверждение') ?></th>
            <th>Управление</th>
            
        </tr>
    </thead>
    <?php
	echo "<pre>";
	print_r($orders);
	echo "</pre>";
        foreach ($orders AS $value)
        {
           /*  echo $this->Html->TableCells(array(
                $this->Time->format('d.m.Y',$value['Order']['created'])
                , $value['Order']['cash'] 
                ,""                    
                ,""                    
                ,""                    
                ,""                    
                ,""                    
                ,""                    
                ,""                    
                ,""                    
                ,""                    
                ,$value['User']['username']
                ,($value['Order']['is_confrim']!=0)?'<div class="text-center"><i class="fa fa-check fa-3 img-circle text-success"><i><div>':''              
                ,array('<div class="btn-group">' . $this->ActionPanel->DrawPanel($action_menu,$value['Order']['id']) . '</div>',array('class'=>''))
            )); */
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

