<?php ?>
<div class="row">
        <div id="breadcrumb" class="col-xs-12">
                <ol class="breadcrumb">
                        <li class="hidden-xs"><a href="#">К началу</a></li>
                        <li><a href="#">Движение денежных средств</a></li>
                        <li><?php echo $this->Html->link((($view == 'cash_tmp')?'<b class="text-danger">Невыясненные</b>':(($view == 'cash_in')?'Доходы':'Расходы')),$view,array('escape' => false, 'class' => 'ajax-link')); ?></li>
                        <li><a href="#">Добавить</a></li>
                </ol>
        </div>
</div>

<div class="col-xs-12 col-sm-7">
    <div class="panel panel-default">
        <div class="panel-heading">
            Добавление записи
        </div>
        <div class="panel-body">

        <?php
            echo $this->Form->create('CashFlow', array('url' => './save', 'class' => 'form', 'role' => 'form'));
            echo $this->Form->input('CashFlow.id',array('type' => 'hidden'));

            if(isset($this->data['CashFlow']['is_tmp_type'])&&$this->data['CashFlow']['is_tmp_type'] == 1 )
                echo $this->Form->input('CashFlow.type_id',array(
                                   'type' => 'select',
                                    'label' => 'Тип операции'
                                    ,'class' => 'chosen-select'
                                    ,'div' => array('class' => 'form-group bg-danger')
                                    ,'options' => $this->data['CashFlow']['Types']
                                    ));
            else
                echo $this->Form->input('CashFlow.type_id',array('type' => 'hidden'));
                echo $this->Form->input('CashFlow.is_tmp_type',array('type' => 'hidden'));
        ?>
            <div class="form-group">
                <label>Дата</label>
                <div class="input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                    <?php echo $this->Form->input('CashFlow.created',array('label' => false,'div' => false,'wrap' => false, 'type' => 'text','class' => 'form-control input_date date')); ?>
                </div>
            </div>


        <div class="row">
            <div class="col-sm-8">
                <?php
                    echo $this->Form->input('CashFlow.org_id',array(
                        'type' => 'select',
                        'label' => 'Организация',
                        'class' => 'chosen-select',
                        'options' => $this->data['CashFlow']['Orgs']
                    ));
                ?>
            </div>
            <div class="col-sm-4">
                <?php
                    echo $this->Form->input('CashFlow.org_account_id',array(
                        'type' => 'select',
                        'label' => 'Банк',
                        'class' => 'chosen-select',
                        'options' => $this->data['CashFlow']['AccountOrg'],
                        'disabled' => ($this->data['CashFlow']['pay_type_id']==2)?'disabled':false
                    ));
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-8">
                <?php
                    echo $this->Form->input('CashFlow.cash',array('label' => 'Сумма'));
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-8">
                <?php echo $this->Html->link('изменить','/contragents/edit_dialog',array('escape' => false, 'class' => 'btn btn-info btn-xs ajax-link dialog-link contragent-edit pull-right')); ?>
                <label for="CashFlowContragentId" class="control-label">Контрагент</label>
                <?php
                    echo $this->Form->input('CashFlow.contragent_id',array(
                        'id' => 'contragent-chosen-select',
                        'type' => 'select',
                        'label' => false,//'Контрагент',
                        'class' => 'chosen-select form-control',
                        'options' => $this->data['CashFlow']['Contragents'],
                        //'div' => false
                    ));
                ?>
            </div>
            <div class="col-sm-4">
                <?php
                    echo $this->Form->input('CashFlow.contragent_account_id',array(
                        'type' => 'select',
                        'label' => 'Банк',
                        'class' => 'chosen-select',
                        'options' => $this->data['CashFlow']['AccountContragent'],
                        'disabled' => ($this->data['CashFlow']['pay_type_id']==2)?'disabled':false
                    ));
                ?>
            </div>
        </div>
        <?php
            echo $this->Form->input('CashFlow.pay_type_id',array(
                                   'type' => 'select',
                                    'label' => 'Способ оплаты'
                                    ,'class' => 'chosen-select'
                                    ,'options' => $this->data['CashFlow']['PayTypes']
                                    ));
            echo $this->Form->input('CashFlow.article_id',array(
                                   'type' => 'select',
                                    'label' => 'Статья'
                                    ,'class' => 'chosen-select'
                                    ,'options' => $this->data['CashFlow']['Articles']
                                    ));
            echo $this->Form->input('CashFlow.nomenclature_id',array(
                                   'type' => 'select',
                                    'label' => 'Номенклатура'
                                    ,'class' => 'chosen-select'
                                    ,'options' => $this->data['CashFlow']['Nomenclatures']
                                    ));
            echo $this->Form->input('CashFlow.number',array('label' => 'Номер документа'));
            echo $this->Form->input('CashFlow.quantity',array('label' => 'Количество'));
            echo $this->Form->input('CashFlow.classificator_id',array(
                                   'type' => 'select',
                                    'label' => (($view == 'cash_in')?'Розница/Опт':'Классификация')
                                    ,'class' => 'chosen-select'
                                    ,'options' => $this->data['CashFlow']['Classificators']
                                    ));
            echo $this->Form->input('CashFlow.source_id',array(
                                    'type' => 'select',
                                    'label' => 'Источник'
                                    ,'class' => 'chosen-select'
                                    ,'options' => $this->data['CashFlow']['Sources']
            ));
            echo $this->Form->input('CashFlow.about',array('label' => 'Примечание','type' => 'textarea',));
            echo '<div class="control-group"><div class="checkbox"><label>'
                . $this->Form->checkbox('CashFlow.accepted') . '<b>Принято к учету</b></label></div></div>';
//            echo $this->Html->link('Копировать','copy_operation',array('escape' => false, 'class' => 'ajax-link btn btn-info pull-right'));
            echo '<div class="btn-group">' . $this->Form->submit('Сохранить',array('class' => 'btn btn-success ajax-link validate-form','div' => false))
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
        }).on('change', function(evt, params) {
            var formObj = $(this).parents('form');
            var url = '<?php echo $this->Html->url(array('controller' => $this->params['controller'], 'action' => 'change')); ?>'
            var data = formObj.serialize();
            LoadAjaxContent(url,data);
        });

        $("form").on('changeeditform', function(evt, params) {
            /*Вставим новый элемент и выберем его*/
            $("#contragent-chosen-select").append($("<option></option>")
                                .attr("value",params)
                                .text(0));
            $("#contragent-chosen-select").val(params);
            /*Отправим событие*/
            $(".chosen-select").trigger('change');
        });

        $(".contragent-edit").attr('href',$(".contragent-edit").attr('href') + '/' + $("#contragent-chosen-select").val());

       $('.input_date').datetimepicker({defaultDate: new Date()/*, viewMode: 'years'*/,format: 'DD.MM.YYYY'});
    });
</script>