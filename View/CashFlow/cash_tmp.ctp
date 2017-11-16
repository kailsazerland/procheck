<?php 
    $this->Paginator->options(array('class' => 'ajax-link','url' => $this->passedArgs));
?>

<div class="row">
        <div id="breadcrumb" class="col-xs-12">
                <ol class="breadcrumb">
                        <li class="hidden-xs"><a href="#">К началу</a></li>
                        <li><a href="#">Движение денежных средств</a></li>
                        <li><a href="#">Невыясненные</a></li>
                </ol>
        </div>
</div>

<div class="row">
    <div class="navbar-form">
        <?php echo $this->element('search',array('search_view' => 'cash_tmp', 'class' => 'navbar-text')); ?> 
    </div>
</div>   
<div class="table-responsive">
<table class='table table-hover table-striped table-bordered table-condensed' id="datatable">
    <thead>
        <tr>
            <th><?php echo $this->Admin->PaginatorSort('created','Дата') ?></th>
            <th class="hidden-xs"><?php echo $this->Admin->PaginatorSort('Org.name','Организация') ?></th>
            <th><?php echo $this->Admin->PaginatorSort('cash','Сумма') ?></th>
            <th class="hidden-xs"><?php echo $this->Admin->PaginatorSort('Contragent.name','Контрагент') ?></th>
            <th class="hidden-xs"><?php echo $this->Admin->PaginatorSort('PayType.name','Способ оплаты') ?></th>
            <th class="hidden-xs"><?php echo $this->Admin->PaginatorSort('Article.name','Статья') ?></th>
            <th class="hidden-xs"><?php echo $this->Admin->PaginatorSort('Nomenclature.name','Номенклатура') ?></th>
            <th class="hidden-xs"><?php echo $this->Admin->PaginatorSort('number','Номер документа') ?></th>
            <th class="hidden-xs hidden-sm"><?php echo $this->Admin->PaginatorSort('quantity','Количество') ?></th>            
            <th class="hidden-xs hidden-sm hidden-md">Примечание</th>
            <th class="hidden-xs hidden-sm hidden-md"><?php echo $this->Admin->PaginatorSort('User.username','Автор') ?></th>            
            <th class="visible-xs visible-sm">Доп.</th>
            <th class="hidden-xs">Управление</th>            
        </tr>
    </thead>
    <?php 
        foreach ($cash_tmp AS $cash)
        {
            echo $this->Html->TableCells(array(
                 $this->Time->format('d.m.Y',$cash['CashFlow']['created'])
                ,array($cash['Org']['name'],array('class'=>'hidden-xs'))
                ,$cash['CashFlow']['cash']
                ,array($cash['Contragent']['name'],array('class'=>'hidden-xs'))
                ,array($cash['PayType']['name'],array('class'=>'hidden-xs'))
                ,array($cash['Article']['name'],array('class'=>'hidden-xs'))
                ,array($cash['Nomenclature']['name'],array('class'=>'hidden-xs'))
                ,array($cash['CashFlow']['number'],array('class'=>'hidden-xs'))
                ,array($cash['CashFlow']['quantity'],array('class'=>'hidden-xs hidden-sm'))
                ,array($cash['CashFlow']['about'],array('class'=>'hidden-xs hidden-sm hidden-md'))
                ,array($cash['User']['username'],array('class'=>'hidden-xs hidden-sm hidden-md'))
                ,array('<div class="right-panel-toolbar"><div class="btn btn-success"><i class="fa fa-level-down"></i></div></div>',array('class'=>'table-xs-panel-button visible-xs visible-sm'))                
                ,array('<div class="btn-group">' . $this->ActionPanel->DrawPanel($action_menu,$cash['CashFlow']['id']) . '</div>',array('class'=>'hidden-xs'))
            ));
        }
    ?>    
</table>
</div>

<div class="navbar-fixed-bottom">
    <div id="slider" class="pull-right"></div>
</div>  

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
<?php //echo $this->Js->writeBuffer(); ?>

<script type="text/javascript">
    $(document).ready(function() {
        $(".table-xs-panel-button").on('click',function(){
            var panel = $(this).parents('tr').next();
            if (panel.hasClass('active') == false) {
                $(this).parents('tr').next().show().addClass('active');
            } else $(this).parents('tr').next().hide().removeClass('active');
        });
        
        
        var currentPos = $('.table-responsive').scrollLeft();
        var outerWidth = $('.table-responsive').outerWidth();  
        $("#slider").width($('.table-responsive').width()).slider({
            max: outerWidth / 2,
            slide: function (event, ui) {
                $('.table-responsive').scrollLeft(ui.value);
            }
        });        
        
    });
</script>
