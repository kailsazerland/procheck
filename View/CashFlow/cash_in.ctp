<?php 
    $this->Paginator->options(array('class' => 'ajax-link','url' => $this->passedArgs));
?>

<div class="row">
        <div id="breadcrumb" class="col-xs-12">
                <ol class="breadcrumb">
                        <li class="hidden-xs"><a href="#">К началу</a></li>
                        <li><a href="#">Движение денежных средств</a></li>
                        <li><a href="#">Доходы</a></li>
                </ol>
        </div>
</div>

<div class="row">
    <div class="navbar-form">
        <div class="navbar-btn navbar-left">
            <?php echo $this->Html->link('Добавить','add_cash_flow_in',array('class' => 'btn btn-success ajax-link'));?>
            <?php echo $this->Html->link('Выгрузка данных в EXCEL','export/in',array('class' => 'btn btn-info'));?>            
            <?php echo $this->Html->link('Фильтр' . (($this->data['FilterBox']['filtered']==1)?' <i class="fa fa-1 fa-check text-success"></i>':''),'#',array('class' => 'btn btn-info btn-cash-filter','escape' => false));?>            
        </div>    
        <?php echo $this->element('search',array('search_view' => 'cash_in', 'class' => 'navbar-text')); ?> 
    </div>
</div>   
<div class="cash-filter" style="display: none;">
    <div class="panel panel-default">
        <div class="panel-heading">
            Фильтр
        </div>
        <div class="panel-body">   
        <?php    
            echo $this->Form->create('FilterBox', array('url' => 'search/cash_in', 'class' => 'form', 'role' => 'form'));
        ?>
            <div class="row">    
                <div class="col-xs-3">    
                    <div class="form-group">
                        <label>Дата с</label>
                        <div class="input-group">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            <?php echo $this->Form->input('FilterBox.created_b',array('label' => false,'div' => false,'wrap' => false, 'type' => 'text','class' => 'form-control input_date date')); ?>
                        </div>
                    </div> 
                </div>  
                <div class="col-xs-3">    
                    <div class="form-group">
                        <label>Дата по</label>
                        <div class="input-group">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            <?php echo $this->Form->input('FilterBox.created_e',array('label' => false,'div' => false,'wrap' => false, 'type' => 'text','class' => 'form-control input_date date')); ?>
                        </div>
                    </div> 
                </div>                   
                <div class="col-xs-3">
                    <?php
                        echo $this->Form->input('FilterBox.sum_min',array(
                            'type' => 'text',
                            'label' => 'Сумма ОТ'
                        ));
                    ?>
                </div>                
                <div class="col-xs-3">
                    <?php
                        echo $this->Form->input('FilterBox.sum_max',array(
                            'type' => 'text',
                            'label' => 'Сумма ДО'
                        ));
                    ?>
                </div>
<!--                <div class="col-xs-6"> 
                    <div class="form-group">
                        <label>Сумма:</label>        
                        <?php
                            /*echo $this->Form->input('FilterBox.cash',array('label' => false, 'id' => 'cash_range', 'div' => false,
                                'class' => 'span2', 'data-slider-min' => '0', 'data-slider-max' => '10000000', 
                                'data-slider-step' => '5', 'data-slider-value' => '[0,2500000]', 
                                ));*/      
                        ?> 
                        <span id="cash-label" class="small">с 0.00р. по 2,500,000.00р.</span> 
                    </div>  
                </div>   
-->
            </div> 
            <div class="row">  
                <div class="col-xs-3">
                    <?php
                        echo $this->Form->input('FilterBox.org_id',array(
                            'type' => 'select',
                            'label' => 'Организация',
                            'class' => 'chosen-select',
                            'options' => $this->data['FilterBox']['Orgs']
                        ));
                    ?>
                </div>                 
                <div class="col-xs-3">
                    <?php
                        echo $this->Form->input('FilterBox.contragent_id',array(
                            'type' => 'select',
                            'label' => 'Контрагент',
                            'class' => 'chosen-select',
                            'options' => $this->data['FilterBox']['Contragents']
                        ));
                    ?>
                </div>                 
                <div class="col-xs-3">
                    <?php
                        echo $this->Form->input('FilterBox.pay_type_id',array(
                            'type' => 'select',
                            'label' => 'Способ оплаты',
                            'class' => 'chosen-select',
                            'options' => $this->data['FilterBox']['PayTypes']
                        ));
                    ?>
                </div>   
                <div class="col-xs-3">
                    <?php
                        echo $this->Form->input('FilterBox.article_id',array(
                            'type' => 'select',
                            'label' => 'Статья',
                            'class' => 'chosen-select',
                            'options' => $this->data['FilterBox']['Articles']
                        ));
                    ?>
                </div>      
            </div>
            <div class="row">  
                <div class="col-xs-3">
                    <?php
                        echo $this->Form->input('FilterBox.nomenclature_id',array(
                            'type' => 'select',
                            'label' => 'Номенклатура',
                            'class' => 'chosen-select',
                            'options' => $this->data['FilterBox']['Nomenclatures']
                        ));
                    ?>
                </div>                 
                  <div class="col-xs-3">
                    <?php
                        echo $this->Form->input('FilterBox.classificator_id',array(
                            'type' => 'select',
                            'label' => 'Розница/опт',
                            'class' => 'chosen-select',
                            'options' => $this->data['FilterBox']['Classificators']
                        ));
                    ?>
                </div>  
                  <div class="col-xs-3">
                    <?php
                        echo $this->Form->input('FilterBox.autor',array(
                            'type' => 'select',
                            'label' => 'Автор',
                            'class' => 'chosen-select',
                            'options' => $this->data['FilterBox']['Autors']
                        ));
                    ?>
                </div>    
                  <div class="col-xs-3">
                    <?php
                        echo $this->Form->input('FilterBox.accepted',array(
                            'type' => 'select',
                            'label' => 'Учет',
                            'class' => 'chosen-select',
                            'options' => array(0 => 'Нет', 1 => 'Да')
                        ));
                    ?>
                </div>                  
            </div>            
        <?php 
            echo '<div class="btn-group pull-right">' . $this->Form->submit('Сбросить',array('class' => 'btn btn-danger ajax-link','name' => 'reset','div' => false));
            echo $this->Form->submit('Применить',array('class' => 'btn btn-success ajax-link pull-right','div' => false)) . '</div>'; 
            echo $this->Form->end();            
        ?>    
 
        </div>
    </div>
</div>
<div class="table-responsive">
<table class='table table-hover table-striped table-bordered table-condensed' id="datatable">
    <thead>
        <tr>
            <th><?php echo $this->Admin->PaginatorSort('created','Дата') ?></th>
            <th class="hidden-xs"><?php echo $this->Admin->PaginatorSort('Org.name','Организация') ?></th>
            <th><?php echo $this->Admin->PaginatorSort('cash','Доход (сумма)') ?></th>
            <th class="hidden-xs"><?php echo $this->Admin->PaginatorSort('Contragent.name','Контрагент') ?></th>
            <th class="hidden-xs"><?php echo $this->Admin->PaginatorSort('PayType.name','Способ оплаты') ?></th>
            <th class="hidden-xs"><?php echo $this->Admin->PaginatorSort('Article.name','Статья') ?></th>
            <th class="hidden-xs"><?php echo $this->Admin->PaginatorSort('Nomenclature.name','Номенклатура') ?></th>
            <th class="hidden-xs"><?php echo $this->Admin->PaginatorSort('number','Номер документа') ?></th>
            <th class="hidden-xs hidden-sm"><?php echo $this->Admin->PaginatorSort('quantity','Кол.') ?></th>
            <th class="hidden-xs hidden-sm"><?php echo $this->Admin->PaginatorSort('Classificator.name','Розница/Опт.') ?></th>
            <th class="hidden-xs hidden-sm hidden-md">Учет</th>
            <th class="hidden-xs hidden-sm hidden-md">Примечание</th>
            <th class="hidden-xs hidden-sm hidden-md"><?php echo $this->Admin->PaginatorSort('User.username','Автор') ?></th>            
            <th class="visible-xs visible-sm">Доп.</th>
            <th class="hidden-xs">Управление</th>            
        </tr>
    </thead>
    <?php
        foreach ($cash_in AS $cash)
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
                ,array($cash['Classificator']['name'],array('class'=>'hidden-xs hidden-sm'))
                ,array((($cash['CashFlow']['accepted']==1)?'<i class="fa fa-check"></i>':''),array('class'=>'hidden-xs hidden-sm hidden-md'))
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
        
   
        //$( "#slider" ).width($("#content" ).width()).offset({left: $("#sidebar-left").width()}).slider();
//console.log($('.table-responsive').width());        
        var currentPos = $('.table-responsive').scrollLeft();
        var outerWidth = $('.table-responsive').outerWidth();  
        $("#slider").width($('.table-responsive').width()).slider({
            max: outerWidth / 2,
            slide: function (event, ui) {
                $('.table-responsive').scrollLeft(ui.value);
            }
        });
        
        //jQuery('.my-table-responsive').scrollbar();
        
        
/**** Для фильтр бокса ****/
        $(".chosen-select").chosen({
            no_results_text:"Совпадений не обнаружено",
            search_contains:true,
            placeholder_text_single:"Выберите значение",
            width: "100%"
        });
        
        $(".btn-cash-filter").on('click',function(){
            if ($(".cash-filter").is(':visible')) {                
                $(".cash-filter").hide("slow");
            } else {              
                $(".cash-filter").show("slow");                
            }
        });
        
        $('.input_date').datetimepicker({defaultDate: new Date()/*, viewMode: 'years'*/,format: 'DD.MM.YYYY'});
        
 /*       var slider = new Slider('#cash_range', {
            tooltip: 'hide',
            //formatter: function(value) {         
            //  return 'С ' + value[0] + ' по ' + value[1] + ' руб.';
            //}            
      
        }).on('slide',function(value){
            $('#cash-label').text('с ' + value[0].toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,') + 'р. по ' + value[1].toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,') + 'р.');
        });
*/        
/***************************/
    });
</script>
