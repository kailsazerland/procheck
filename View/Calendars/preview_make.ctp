<?php ?>
<div class="row">
        <div id="breadcrumb" class="col-xs-12">
                <ol class="breadcrumb">
                        <li class="hidden-xs"><a href="#">К началу</a></li>
                        <li><a href="#">Бюджетирование</a></li>
                        <li><a href="#">Платежный календарь</a></li>
                        <li><a href="#">Формировать</a></li>
                </ol>
        </div>
</div>

<div class="col-xs-12 col-sm-6">
    <div class="panel panel-default">
        <div class="panel-heading">
            Формирование платежного календаря за период
        </div>
        <div class="panel-body">

        <?php
            echo $this->Form->create('Calendar', array('url' => './make', 'class' => 'form', 'role' => 'form'));
            echo $this->Form->input('Calendar.id',array('type' => 'hidden', 'value' => 0));
            echo $this->Form->input('Calendar.name',array('label' => 'Наименование'));
        ?>            
            <div class="form-group">
            <div class="row">
                
            <div class="col-xs-12 col-sm-12">
                <div class="input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                    <?php echo $this->Form->input('Calendar.period',array('label' => false,'div' => false,'wrap' => false, 'type' => 'text','class' => 'form-control input_date date')); ?>
                </div>
            </div>                  
                
            </div>
            </div>     
        <?php 
            echo $this->Form->input('Calendar.template_calendar_id',array(
                                   'type' => 'select',
                                    'label' => 'Шаблон платежного календаря'
                                    ,'class' => 'chosen-select'
                                    ,'options' => $templates
                                    ));
            echo '<div class="btn-group">' . $this->Form->submit('Сформировать',array('class' => 'btn btn-success ajax-link','div' => false)) 
                    . $this->Form->submit('Отмена',array('class' => 'btn btn-danger ajax-link','div' => false,'name' => 'cancel')) . '</div>'; 
            echo $this->Form->end();
        ?>            
            
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
        
        $('.input_date').datetimepicker({defaultDate: new Date()/*, viewMode: 'years'*/,format: '01.MM.YYYY'});
    });
</script>
