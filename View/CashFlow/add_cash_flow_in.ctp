<?php ?>
<div class="row">
        <div id="breadcrumb" class="col-xs-12">
                <ol class="breadcrumb">
                        <li class="hidden-xs"><a href="#">К началу</a></li>
                        <li><a href="#">Движение денежных средств</a></li>
                        <li><a href="#">Доходы</a></li>
                        <li><a href="#">Добавить</a></li>
                </ol>
        </div>
</div>

<div class="col-xs-12 col-sm-6">
    <div class="panel panel-default">
        <div class="panel-heading">
            Добавление обьекта
        </div>
        <div class="panel-body">

        <?php
            echo $this->Form->create('CashFlow', array('url' => './save/cash_in', 'class' => 'form', 'role' => 'form'));		
            echo $this->Form->input('CashFlow.id',array('type' => 'hidden')); 
            echo $this->Form->input('CashFlow.type',array('type' => 'hidden')); 
            echo $this->Form->input('CashFlow.number',array('label' => 'Номер документа'));
            echo $this->Form->input('CashFlow.org_id',array(
                                   'type' => 'select',
                                    'label' => 'Организация'
                                    ,'class' => 'chosen-select'
                                    ,'options' => array()
                                    ));
            echo $this->Form->input('CashFlow.contragent_id',array(
                                   'type' => 'select',
                                    'label' => 'Контрагент'
                                    ,'class' => 'chosen-select'
                                    ,'options' => $this->data['CashFlow']['Contragent']
                                    ));
            echo $this->Form->input('CashFlow.pay_type_id',array(
                                   'type' => 'select',
                                    'label' => 'Способ оплаты'
                                    ,'class' => 'chosen-select'
                                    ,'options' => array()
                                    ));
            echo $this->Form->input('CashFlow.articles_id',array(
                                   'type' => 'select',
                                    'label' => 'Статья'
                                    ,'class' => 'chosen-select'
                                    ,'options' => array()
                                    ));
            echo $this->Form->input('CashFlow.nomenclature_id',array(
                                   'type' => 'select',
                                    'label' => 'Номенклатура'
                                    ,'class' => 'chosen-select'
                                    ,'options' => $this->data['CashFlow']['Nomenclature']
                                    ));
            echo $this->Form->input('CashFlow.classificator_id',array(
                                   'type' => 'select',
                                    'label' => 'Вид операции'
                                    ,'class' => 'chosen-select'
                                    ,'options' => array()
                                    ));
            echo $this->Form->input('CashFlow.cash',array('label' => 'Доход (сумма)'));
            echo $this->Form->input('CashFlow.quantity',array('label' => 'Количество'));
            echo $this->Form->input('CashFlow.about',array('label' => 'Примечание','type' => 'textarea',));
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
    });
</script>