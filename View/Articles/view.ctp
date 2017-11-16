<?php 
    $this->Paginator->options(array('class' => 'ajax-link','url' => $this->passedArgs));
?>
<div class="row">
        <div id="breadcrumb" class="col-xs-12">
                <ol class="breadcrumb">
                        <li class="hidden-xs"><a href="#">К началу</a></li>
                        <li><a href="#">Справочники</a></li>
                        <li><a href="#">Статьи доходов и расходов</a></li>
                </ol>
        </div>
</div>


<div class="row">
    <div class="navbar-form">
        <div class="navbar-btn navbar-left">            
            <?php echo $this->Html->link('<i class="fa fa-arrow-left"></i>&nbspНазад','type_view',array('class' => 'btn btn-primary ajax-link','escape' => false));?>            
        </div>    
        <?php echo $this->element('search',array('search_view' => 'view/' . current($articles)['Article']['type_id'], 'class' => 'navbar-text')); ?> 
    </div>
</div>  


<table class='table table-hover table-striped table-bordered table-condensed'>
    <thead>
        <tr>
            <th><?php echo $this->Admin->PaginatorSort('name','Наименование') ?></th>            
            <th><?php echo $this->Admin->PaginatorSort('Type.name','Тип') ?></th>
            <th>Управление</th>
            
        </tr>
    </thead>
    <?php
        foreach ($articles AS $value)
        {
            echo $this->Html->TableCells(array(
                 $value['Article']['name']
                ,$value['Type']['name']
                ,array('<div class="btn-group">' . $this->ActionPanel->DrawPanel($action_menu,$value['Article']['id']) . '</div>',array('class'=>''))
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

