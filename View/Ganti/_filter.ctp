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
            <?php echo $this->Form->create('Filter', array('url' => 'view', 'class' => 'form form-horizontal', 'role' => 'form'));	?>                        
                <div class="col-xs-12 col-sm-3">
                    <div class="input-group date">
                        <span class="input-group-addon">период с</span>
                        <?php echo $this->Form->input('Filter.date_start',array('label' => false,'div' => false,'wrap' => false,'class' => 'form-control input_date date')); ?>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-3">
                    <div class="input-group date">
                        <span class="input-group-addon">по</span>
                        <?php echo $this->Form->input('Filter.date_end',array('label' => false,'div' => false,'wrap' => false,'class' => 'form-control input_date date')); ?>
                    </div>
                </div>                

                    <?php echo $this->Form->input('Filter.article_id',array(
                                               'type' => 'select'
                                                ,'label' => false
                                                ,'class' => 'chosen-select'
                                                ,'options' => $articles
                                                ,'selected' => array_keys($articles)
                                                ,'multiple' => true
                                                ,'data-placeholder' => 'Выберите статьи...'
                                                ,'div' => 'col-xs-12 col-sm-6'
                                                ,'wrap' => false
                                                ));	?>

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
                    <?php echo $this->Form->submit('Применить',array('class' => 'btn btn-success ajax-link','div' => false)); ?>
                    <a href="#" id="clear-articles" class="btn btn-info">Очистить</a>
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
        
    $("#clear-articles").click(function (e) { 
            $('.chosen-select option:selected').removeAttr('selected');
            $('.chosen-select').trigger('chosen:updated');
        });
        
    $('.input_date').datetimepicker({defaultDate: new Date()/*, viewMode: 'years'*/,format: 'DD.MM.YYYY'});
    
    });
</script>