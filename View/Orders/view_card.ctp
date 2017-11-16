<?php 
    $this->Paginator->options(array('class' => 'ajax-link','url' => $this->passedArgs));
?>
<div class="row">
        <div id="breadcrumb" class="col-xs-12">
                <ol class="breadcrumb">
                        <li class="hidden-xs"><a href="#">К началу</a></li>
                        <li><?php echo $this->Html->link('Заявки','view',array('class' => 'ajax-link')); ?></li>
                        <li><a href="#">Карточка расхода</a></li>
                </ol>
        </div>
</div>

<div class="container-fluid">
        <div class="navbar-btn navbar-left">
            <?php echo $this->Html->link('<i class="fa fa-arrow-left"></i>&nbspНазад','view',array('class' => 'btn btn-primary ajax-link','escape' => false));?>
            <?php echo $this->Html->link('Добавить','add_card/' . $this->data['Order']['id'],array('class' => 'btn btn-success ajax-link dialog-link'));?>                      
            <?php echo $this->Html->link('Выгрузка данных в EXCEL','export/' . $this->data['Order']['id'],array('class' => 'btn btn-info'));?>             
        </div>            
</div>  

<div class="col-xs-12 col-sm-12">
    <table class='table table-hover table-striped table-bordered table-condensed'>
        <thead>
            <tr>
                <th><?php echo $this->Admin->PaginatorSort('Article.name','Статья расходов') ?></th>
                <th><?php echo $this->Admin->PaginatorSort('Contragent.name','Контрагент') ?></th>
                <th><?php echo $this->Admin->PaginatorSort('cash','Сумма') ?></th>
                <th><?php echo $this->Admin->PaginatorSort('','НДС') ?></th>
                <th><?php echo $this->Admin->PaginatorSort('PayType.name','Способ оплаты') ?></th>
                <th><?php echo $this->Admin->PaginatorSort('about','Описание') ?></th>
                <th>Управление</th>

            </tr>
        </thead>
        <?php
            foreach ($cards AS $value)
            {
                echo $this->Html->TableCells(array(
                     $value['Article']['name']
                    ,$value['Contragent']['name']
                    ,$value['OrderCard']['cash']
                    ,$value['Nalog']['name']
                    ,$value['PayType']['name']                    
                    ,$value['OrderCard']['about']                                    
                    ,array('<div class="btn-group">' . $this->ActionPanel->DrawPanel($action_menu,$value['OrderCard']['id']) . '</div>',array('class'=>''))
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

<?php if($this->data['Order']['is_confrim'] != -1): ?>
    <div class="col-xs-12 col-sm-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                Отметка руководителя
            </div>        
            <div class="panel-body">
                <!--<div class="toggle-switch toggle-switch-success">  
                    <?php //echo $this->Form->create('Order', array('url' => './save', 'class' => 'form', 'role' => 'form')); ?>                
                    <label>
                        <?php //echo $this->Form->input('Order.id',array('type' => 'hidden')); 
                              //echo $this->Form->checkbox('Order.is_confrim',array('id' => 'is_confrim'));
                        ?>
                        <div class="toggle-switch-inner"></div>
                        <div class="toggle-switch-switch"><i class="fa fa-check"></i></div>
                    </label>
                    <?php //echo $this->Form->end(); ?>                
                </div>-->

                <?php 
                    if($this->data['Order']['is_confrim'] == 0)
                        echo ($confirm_permitted)?$this->Html->link('Утвердить','confirm/' . $this->data['Order']['id'],array('class' => 'btn btn-success ajax-link pull-right')):'';
                    else { 
                        echo '<div class="sign-txt pull-right">УТВЕРЖДЕНО</div><br>';
                        echo ($confirm_permitted)?$this->Html->link('Отменить утверждение','delete_confirm/' . $this->data['Order']['id'],array('class' => 'btn btn-danger ajax-link pull-right')):'';
                    }
                ?>

            </div>  
        </div>
    </div> 
<?php endif; ?>

<script type="text/javascript">
    $(document).ready(function() {
        $(".chosen-select").chosen({
            no_results_text:"Совпадений не обнаружено",
            search_contains:true,
            placeholder_text_single:"Выберите значение",
            width: "100%"
        });      
        
        /*$('#is_confrim').on('click',function(){
            if(!confirm("Утвердиь заявку?")) return false;
            var formObj = $(this).parents('form');            
            var formURL = formObj.attr("action");
            var formData = formObj.serialize();            
            $.ajax({
                    mimeType: 'text/html; charset=utf-8',
                    url: formURL,
                    data: formData,
                    type: 'POST',
                    dataType: "html",
                    async: false
            });
        });*/
        
    });
</script>