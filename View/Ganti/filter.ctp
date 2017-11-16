<?php ?>
<div class="row">
        <div id="breadcrumb" class="col-xs-12">
                <ol class="breadcrumb">
                        <li class="hidden-xs"><a href="#">К началу</a></li>
                        <li><a href="#">Бюджетирование</a></li>
                        <li><a href="#">Платежный календарь</a></li>
                </ol>
        </div>
</div>

<div class="col-xs-12 col-sm-12">
    <div class="panel panel-default">
        <div class="panel-heading">
            Фильтр
        </div>
        <div class="panel-body">
            <div class="row show-grid-forms">            
            <?php echo $this->Form->create('Filter', array('url' => 'view', 'class' => 'form', 'role' => 'form'));	?>            
<div class="col-xs-6 col-sm-6">
            <?php echo $this->Form->input('Filter.template_id',array(
                                   'type' => 'select',
                                    'label' => 'Шаблон платежного календаря'
                                    ,'class' => 'chosen-select'
                                    ,'options' => $templates
                                    ));	?>
</div>
                <div class="col-xs-6 col-sm-6">
                    <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-primary active">
                                <input type="radio" name="data[Filter][type]" id="option1" value="table" checked="checked"> Таблица
                        </label>
                        <label class="btn btn-primary">
                                <input type="radio" name="data[Filter][type]" id="option2" value="chart"> Диаграмма Ганта
                        </label>
                    </div>  
                </div>
                
                <div class="col-xs-12 col-sm-12">                    
                    <?php echo $this->Form->submit('Применить',array('class' => 'btn btn-success','div' => false)); ?>
                </div>    
                    <?php echo $this->Form->end(); ?>
            </div> 
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $(".chosen-select").chosen({
            no_results_text:"Совпадений не обнаружено",
            search_contains:true,
            placeholder_text_single:"Выберите значение",
            width: "100%"
        });
        
    });
</script>