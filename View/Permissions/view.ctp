<?php 
    $this->Paginator->options(array('class' => 'ajax-link','url' => $this->passedArgs));
?>
<div class="row">
        <div id="breadcrumb" class="col-xs-12">
                <ol class="breadcrumb">
                        <li class="hidden-xs"><a href="#">К началу</a></li>
                        <li><a href="#">Настройки</a></li>
                        <li><a href="#">Настройки прав доступа</a></li>
                </ol>
        </div>
</div>


<div class="row">
    <div class="navbar-form">
        <div class="navbar-btn navbar-left">
            <?php echo $this->Html->link('Добавить','add',array('class' => 'btn btn-success ajax-link'));?>
        </div>    
    </div>
</div>  


<table class='table table-hover table-striped table-bordered table-condensed'>
    <thead>
        <tr>
            <th><?php echo $this->Admin->PaginatorSort('name','Наименование') ?></th>
            <th><?php echo $this->Admin->PaginatorSort('action','Действие') ?></th>
            <th><?php echo $this->Admin->PaginatorSort('is_group','Является группой') ?></th>            
            <th><?php echo $this->Admin->PaginatorSort('about','Описание') ?></th>
            <th>Управление</th>
            
        </tr>
    </thead>
    <?php
        foreach ($permission AS $value)
        {
            echo $this->Html->TableCells(array(
                 $value['Permission']['name']
                ,$value['Permission']['action']
                ,($value['Permission']['is_group']!=0)?'<div class="text-center"><i class="fa fa-check fa-3 img-circle text-success"><i><div>':''  
                ,$value['Permission']['about']
                ,array('<div class="btn-group">' . $this->ActionPanel->DrawPanel($action_menu,$value['Permission']['id']) . '</div>',array('class'=>''))
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

