<?php ?>
<div class="col-xs-12 col-sm-6">
    <div class="panel panel-default">
        <div class="panel-heading">
            Добавление План/факт анализа
        </div>
        <div class="panel-body">

        <?php
            echo $this->Form->create('Budget', array('url' => './save', 'class' => 'form', 'role' => 'form'));		
            echo $this->Form->input('Budget.id',array('type' => 'hidden')); 
            echo $this->Form->input('Budget.type_id',array('type' => 'hidden'));
            echo $this->Form->input('Budget.view',array('type' => 'hidden'));
            //echo '<div class="form-group row"><div class="col-xs-12 col-sm-6">' 
                // $this->Form->input('Budget.period',array('type' => 'date','dateFormat' => 'MY','label' => false,'div' => false,'separator' => '</div><div class="col-xs-12 col-sm-6">')) 
                //. '</div></div>';
            ?>
            <div class="row show-grid-forms">
                <div class="col-xs-12 col-sm-6">
                    <div class="input-group">
                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                        <?php echo $this->Form->input('Budget.period',array('label' => false,'div' => false,'wrap' => false, 'type' => 'text','class' => 'form-control input_date date')); ?>
                    </div>
                </div>  

                 <div class="col-xs-12 col-sm-6"><div class="btn-group">     
                <?php echo $this->Form->submit('Сохранить',array('class' => 'btn btn-success ajax-link','div' => false)) 
                        . $this->Form->submit('Отмена',array('class' => 'btn btn-danger ajax-link','div' => false,'name' => 'cancel')); ?> 
                </div></div>; 
            </div>
            <?php echo $this->Form->end();  ?>
                   
            
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