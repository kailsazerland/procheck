<?php ?>
<div class="row">
        <div id="breadcrumb" class="col-xs-12">
                <ol class="breadcrumb">
                        <li class="hidden-xs"><a href="#">К началу</a></li>
                        <li><a href="#">Справочники</a></li>
                        <li><a href="#">Платежный календарь</a></li>
                        <li><a href="#"><?php echo $calendar['Calendar']['name']; ?></a></li>                        
                        <li><a href="#">Добавить</a></li>
                </ol>
        </div>
</div>

<div class="col-xs-12 col-sm-6">
    <div class="panel panel-default">
        <div class="panel-heading">
            Добавление/редактирование платежного календаря
        </div>
        <div class="panel-body">

        <?php
            echo $this->Form->create('CalendarPay', array('url' => './pay_save', 'class' => 'form', 'role' => 'form'));		
            echo $this->Form->input('CalendarPay.id',array('type' => 'hidden')); 
            echo $this->Form->input('CalendarPay.calendar_id',array('type' => 'hidden')); 
            echo $this->Form->input('CalendarPay.article_id',array(
                                   'type' => 'select',
                                    'label' => 'Статья'
                                    ,'class' => 'chosen-select'
                                    ,'options' => $this->data['CalendarPay']['Articles']
                                    )); 
            echo $this->Form->input('CalendarPay.org_id',array(
                                   'type' => 'select',
                                    'label' => 'Организация'
                                    ,'class' => 'chosen-select'
                                    ,'options' => $this->data['CalendarPay']['Orgs']
                                    ));   
            echo $this->Form->input('CalendarPay.contragent_id',array(
                                   'type' => 'select',
                                    'label' => 'Контрагент'
                                    ,'class' => 'chosen-select'
                                    ,'options' => $this->data['CalendarPay']['Contragents']
                                    ));   
            echo $this->Form->input('CalendarPay.pay_type_id',array(
                                   'type' => 'select',
                                    'label' => 'Способ оплаты'
                                    ,'class' => 'chosen-select'
                                    ,'options' => $this->data['CalendarPay']['PayTypes']
                                    ));  
            echo $this->Form->input('CalendarPay.cash',array('label' => 'План'));            
        ?>
           <div class="form-group">
            <div class="row">
            <div class="col-xs-12 col-sm-6">
                <div class="input-group date">
                    <span class="input-group-addon">период с</span>
                    <?php echo $this->Form->input('CalendarPay.beg',array('type' => 'text','label' => false,'div' => false,'wrap' => false,'class' => 'form-control input_date date')); ?>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6">
                <div class="input-group date">
                    <span class="input-group-addon">по</span>
                    <?php echo $this->Form->input('CalendarPay.end',array('type' => 'text','label' => false,'div' => false,'wrap' => false,'class' => 'form-control input_date date')); ?>
                </div>
            </div>  
            </div>
            </div>
        <?php             
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
        
        $('.input_date').datetimepicker({defaultDate: new Date()/*, viewMode: 'years'*/,format: 'DD.MM.YYYY'});
            
    });
</script>