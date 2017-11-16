<?php ?>
<div class="row">
        <div id="breadcrumb" class="col-xs-12">
                <ol class="breadcrumb">
                        <li class="hidden-xs"><a href="#">К началу</a></li>
                        <li><a href="#">Справочники</a></li>
                        <li><a href="#">Заявки</a></li>
                        <li><a href="#">Добавить</a></li>
                </ol>
        </div>
</div>

<div class="col-xs-12 col-sm-6">
    <div class="panel panel-default">
        <div class="panel-heading">
            Добавление заявок на резервирование денежных средств
        </div>
        <div class="panel-body">

        <?php
            echo $this->Form->create('Order', array('url' => './save', 'class' => 'form', 'role' => 'form'));		
            echo $this->Form->input('Order.id',array('type' => 'hidden')); 
            echo $this->Form->input('Order.cash',array('type' => 'hidden'));
            echo $this->Form->input('Order.user_id',array('type' => 'hidden'));
            echo $this->Form->input('Order.is_confrim',array('type' => 'hidden')); ?>
            
            <div class="input-group date">
                <span class="input-group-addon">Период</span>
                <?php echo $this->Form->input('Order.period',array('label' => false,'type' => 'text','div' => false,'wrap' => false,'class' => 'form-control input_date date')); ?>
            </div>            
            
       <?php
            echo $this->Form->input('Order.otdel_id',array(
                                   'type' => 'select',
                                    'label' => 'ЦФО'
                                    ,'class' => 'chosen-select'
                                    ,'options' => $this->data['Order']['Otdels']
                                    ));
            echo '<div class="btn-group">' . $this->Form->submit('Сохранить',array('class' => 'btn btn-success ajax-link','div' => false)) 
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