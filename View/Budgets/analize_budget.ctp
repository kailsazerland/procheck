<?php 
    $this->Paginator->options(array('class' => 'ajax-link','url' => $this->passedArgs));
?>
<div class="row">
        <div id="breadcrumb" class="col-xs-12">
                <ol class="breadcrumb">
                        <li class="hidden-xs"><a href="#">К началу</a></li>
                        <li class="hidden-xs"<a href="#">Бюджетирование</a></li>
                        <li class="hidden-xs"><a href="#">План/факт анализ</a></li>
                        <li><?php echo $this->Html->link($view_budget['name'],$view_budget['view'],array('class' => 'ajax-link')); ?></li>
                        <li><a href="#"><b class="text-uppercase"><?php echo __d('cake',$this->Time->format('F',$budgets['Budget']['period'])) . ' ' . $this->Time->format('Y',$budgets['Budget']['period']); ?></b></a></li>
                </ol>
        </div>
</div>

<div class="container-fluid">
        <div class="navbar-btn navbar-left">
            <?php echo $this->Html->link('<i class="fa fa-arrow-left"></i>&nbspНазад',$view_budget['view'],array('class' => 'btn btn-primary ajax-link','escape' => false));?>
            <?php echo $this->Html->link('Добавить','add_article/' . $budgets['Budget']['id'],array('class' => 'btn btn-success ajax-link dialog-link'));?>
            <?php echo $this->Html->link('Выгрузка данных в EXCEL','export/' . $budgets['Budget']['id'],array('class' => 'btn btn-info'));?>            
        </div>            
</div>  

<div class="col-xs-12 col-sm-12">
    <table class='table table-hover table-striped table-bordered table-condensed'>
        <thead>
            <tr>
                <th><?php echo $this->Admin->PaginatorSort('Article.name','Статья') ?></th>
                <th><?php echo $this->Admin->PaginatorSort('cash_plan','План') ?></th>
                <th><?php echo $this->Admin->PaginatorSort('cash_fact','Факт') ?></th>
                <th><?php echo $this->Admin->PaginatorSort('difference','Разница план/факт') ?></th>
                <th>Управление</th>

            </tr>
        </thead>
        <?php                
            foreach ($budget_values AS $value)
            {
                echo $this->Html->TableCells(array(
                     $value['Article']['name']
                    ,array($value['BudgetValues']['cash_plan'],array('class' => 'cash_plan'))
                    ,array($value['BudgetValues']['cash_fact'],array('class' => 'editable cash_fact', 'id' => $value['BudgetValues']['id']))
                    ,array($value['BudgetValues']['difference'],array('class' => (($value['BudgetValues']['difference'] < 0)?'bg-red':'') . ' difference'))
                    ,array('<div class="btn-group">' . $this->ActionPanel->DrawPanel($action_menu,$value['BudgetValues']['id']) . '</div>',array('class'=>''))
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
</div>

<script type="text/javascript">
    
    function LoadEditableScript(callback) {
        if(!$.fn.editable){
                $.getScript(cake_base_url + '/lib/jquery_jeditable/jquery.jeditable.js', callback);
        }
        else {
                if (callback && typeof(callback) === "function") {
                        callback();
                }
        }
    }
    
    function AddEditable() {
        $.editable.addInputType('bootstrapinput', {
            element: function(settings, original) {
                var input = $('<input class="form-control" type="number"></input>\n\
                                <span class="input-group-btn"><input class="btn btn-success" value="изменить" type="submit"></input></span>');                
                var div = $('<div class="row input-group"/>');
                div.append(input);
                $(this).append(div);
                return (input);
            },
            /*reset : function(settings, original) {
                  original.reset(this);
            },*/
            plugin: function (settings, original) {
                
            },
        });
        $('.editable').editable(cake_base_url + '/budgets/save_change_budget',{
            type: 'bootstrapinput',
            submit: false,
            cssclass: "form form-horizontal",     
            submitdata : function(value, settings) {                
                var id = 0, column = 0;
                if ($(this).hasClass('cash_plan')) {
                    var cash_fact_col = $(this).next('td');
                    id = cash_fact_col.attr('id');
                    column = 'cash_plan';
                } else if ($(this).hasClass('cash_fact')) {
                    id = $(this).attr('id');
                    column = 'cash_fact';
                }
                return {id: id,column: column};
            },
            callback: function (value, settings) {
                var data = $.parseJSON(value);
                $(this).html(data.cash);
                var difference_col = $(this).siblings('.difference');
                difference_col.html(data.difference);
                if(data.difference < 0)
                    difference_col.addClass('bg-red');
                else difference_col.removeClass('bg-red');
            },            
        });
    }
    
    $(document).ready(function() {
        
        LoadEditableScript(AddEditable);

        $(".chosen-select").chosen({
            no_results_text:"Совпадений не обнаружено",
            search_contains:true,
            placeholder_text_single:"Выберите значение",
            width: "100%"
        });
    });
    
</script>