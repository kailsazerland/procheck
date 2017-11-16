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
            <?php echo $this->Html->link('Добавить','add',array('class' => 'btn btn-success ajax-link'));?>
        </div>    
        <?php echo $this->element('search',array('search_view' => 'view', 'class' => 'navbar-text')); ?> 
    </div>
</div>   

<table class='table table-hover table-striped table-bordered table-condensed'>
    <thead>
        <tr>
            <th><?php echo $this->Admin->PaginatorSort('created','Дата') ?></th>
            <th><?php echo $this->Admin->PaginatorSort('period','Период') ?></th>
            <th><?php echo $this->Admin->PaginatorSort('Otdel.name','ЦФО') ?></th>
            <th><?php echo $this->Admin->PaginatorSort('cash','Общая сумма') ?></th>
            <th><?php echo $this->Admin->PaginatorSort('User.username','Автор') ?></th>
            <th><?php echo $this->Admin->PaginatorSort('is_confrim','Утверждено') ?></th>
            <th>Управление</th>
            
        </tr>
    </thead>
    <?php
        foreach ($orders AS $value)
        {
            echo $this->Html->TableCells(array(
                $this->Time->format('d.m.Y',$value['Order']['created'])
                , $this->Time->format('m.Y',$value['Order']['period'])//__d('cake',$this->Time->format('F',$value['Order']['period']))
                ,$value['Otdel']['name']
                ,$value['Order']['cash']                    
                ,$value['User']['username']
                ,($value['Order']['is_confrim']!=0)?'<div class="text-center"><i class="fa fa-check fa-3 img-circle text-success"><i><div>':''              
                ,array('<div class="btn-group">' . $this->ActionPanel->DrawPanel($action_menu,$value['Order']['id']) . '</div>',array('class'=>''))
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

