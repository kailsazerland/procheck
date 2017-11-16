<?php ?>
<div class="row">
    <div id="breadcrumb" class="col-xs-12">
        <ol class="breadcrumb">
            <li class="hidden-xs"><a href="#">К началу</a></li>
            <li><a href="#">Платежи</a></li>
            <li><a href="#">Новые</a></li>
            <li><a href="#">Добавить</a></li>
        </ol>
    </div>
</div>
<div class="col-xs-12 col-sm-12">
    <div class="panel panel-default">
        <div class="panel-heading">
            Добавление платежа
        </div>
        <div class="panel-body">

            <?php
            echo $this->Form->create('Pay', array('url' => './save', 'class' => 'form', 'role' => 'form'));
            echo $this->Form->input('Pay.id',array('type' => 'hidden'));
            echo $this->Form->input('Pay.user_id',array('type' => 'hidden', 'value' => $auth));
            echo $this->Form->input('Pay.type',array('type' => 'hidden', 'value'=> $type)); ?>


            <div class="col-sm-6">
                <?php echo $this->Form->input('Pay.p_views_id',array(
                    'type' => 'select',
                    'label' => 'Вид операции'
                ,'class' => 'chosen-select'
                ,'options' => $report
                ));?>
            </div>
            <div class="col-sm-6"></div>
            <div style="clear: both"></div>
            <div class="col-sm-6">
                <div class="input-group date">
                    <span class="input-group-addon">Дата</span>
                    <?php echo $this->Form->input('Pay.date',array('label' => false,'type' => 'text','div' => false,'wrap' => false,'class' => 'form-control input_date date')); ?>
                </div>
            </div>
            <div class="col-sm-6"></div>
            <div style="clear: both"></div>
            <div class="col-sm-6">
            <?php echo $this->Form->input('Pay.orgs_id',array(
                'type' => 'select',
                'label' => 'Организация'
            ,'class' => 'chosen-select'
            ,'options' => $organiz
            ));?>
            </div>
            <div class="col-sm-6">
            <?php    echo $this->Form->input('Pay.accounts_id',array(
                'type' => 'select',
                'label' => 'Банк'
            ,'options' => $bank
            )); ?>
            </div>
            <div style="clear: both"></div>
            <div class="col-sm-6">
                <?php    echo $this->Form->input('Pay.contragent_id',array(
                    'type' => 'select',
                    'label' => 'Контрагент'
                ,'class' => 'chosen-select'
                ,'options' => $contragents
                )); ?>
                <a href="/contragents/edit/<?=$contr?>" class="btn btn-primary" target="_blank" id="contr_ajax" data-tooltip="Редактировать контрагента" title="Редактировать контрагента"><i class="fa fa-pencil"></i></a>
                <?//echo "<pre>"; print_r($this->data['Account']['id']); echo "</pre>";?>
            </div>
            <div class="col-sm-6">
                <?php    echo $this->Form->input('Pay.bank_id',array(
                    'type' => 'select',
                    'label' => 'Банк (контрагента)'

                ,'options' => $cbank
                )); ?>
                <a href="/accounts/edit/<?=$accou?>" class="btn btn-primary"  target="_blank" id="acc_ajax" data-tooltip="Редактировать банк контрагента" title="Редактировать банк контрагента"><i class="fa fa-pencil"></i></a>
            </div>
            <div class="col-sm-6">
            <?php echo $this->Form->input('Pay.money',array(
                'type' => 'number',
                'label' => 'Сумма'
            ,'class' => 'form-control'
            ));?>
            </div>
            <div class="col-sm-6">
                <?php    echo $this->Form->input('Pay.article_id',array(
                    'type' => 'select',
                    'label' => 'Статья'
                ,'class' => 'chosen-select'
                ,'options' => $stati
                ));?>
            </div>
            <div class="col-sm-6">
            <?php    echo $this->Form->input('Pay.nalog_id',array(
                'type' => 'select',
                'label' => 'Налог'
            ,'class' => 'chosen-select'
            ,'options' => $nalog
            )); ?>
            </div>
            <div class="col-sm-6">
                <?php    echo $this->Form->input('Pay.p_payment_type_id',array(
                    'type' => 'select',
                    'label' => 'Вид платежа'
                ,'class' => 'chosen-select'
                ,'options' => $classif
                ));?>
            </div>
            <div class="col-sm-6">
            <?php    echo $this->Form->input('Pay.money_nds',array(
                'type' => 'number',
                'label' => 'Сумма НДС'
                ,'class' => 'form-control'
                ,'disabled' => 'disabled'
                ,'value' => $nds
            )); ?>
            </div>
            <div class="col-sm-6">
            <?php    echo $this->Form->input('Pay.p_priorities_id',array(
                'type' => 'select',
                'label' => 'Очередность'
            ,'class' => 'chosen-select'
            ,'options' => $priorit
            ));?>
            </div>

            <div class="col-sm-6"></div>
            <div class="col-sm-6">
            <?php    echo $this->Form->input('Pay.payment',array(
                'type' => 'text',
                'label' => 'Идентификатор платежа'
            ,'class' => 'form-control'
            ));?>
            </div>
            <div style="clear: both"></div>

            <div class="col-sm-12">
            <?php    echo $this->Form->input('Pay.point_payment',array(
                'type' => 'textarea',
                'label' => 'Назначение платежа'
            ,'class' => 'form-control'
            ));?>
            </div>
            <div class="col-sm-6">
           
            </div>
            <div class="col-sm-6">
            <?php

            echo '<div class="btn-group">' . $this->Form->submit('Сохранить',array('class' => 'btn btn-success ajax-link','div' => false))
                . $this->Form->submit('Отмена',array('class' => 'btn btn-danger ajax-link','div' => false,'name' => 'cancel')) . '</div>';
            echo $this->Form->end();
            ?>
            </div>

        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#PayMoney').change(function() {
            var money_nds, textArea;
            var money = $(this).val();
//            console.log(money);
            var nalog = $('#PayNalogId').val();
//            console.log(nalog);
            textArea = "Сумма: "+money+" руб. \n";
            if(nalog == '1'){
                money_nds = money*18/118;
                money_nds = money_nds.toFixed(2);

                textArea += "В т.ч. НДС (18%): "+money_nds+" руб. \n";
            }else{
                money_nds = 0;
                textArea += "Без НДС \n";
            }
//            console.log(money_nds);
            $('#PayPointPayment').val(textArea);
            $('#PayMoneyNds').val(money_nds);
        });
        $('#PayNalogId').change(function() {
            var money_nds, textArea;
            var nalog = $(this).val();
//            console.log(money);
            var money = $('#PayMoney').val();
            textArea = "Сумма: "+money+" руб. \n";
//            console.log(nalog);
            if(nalog == '1'){
                money_nds = money*18/118;
                money_nds = money_nds.toFixed(2);

                textArea += "В т.ч. НДС (18%): "+money_nds+" руб. \n";
            }else{
                money_nds = 0;
                textArea += "Без НДС \n";
            }
//            console.log(money_nds);
            $('#PayPointPayment').val(textArea);
            $('#PayMoneyNds').val(money_nds);
        });
    $('#PayBankId').change(function() {
        var id = $(this).val();
        $('#acc_ajax').attr('href', '/accounts/edit/' + id);
    });

    $('#PayContragentId').change(function() {
//        PayBankId
        var id = $(this).val();
        $('#contr_ajax').attr('href', '/contragents/edit/' + id );
        $.post('/ajax/org.php', {id_org: $(this).val()}, function (data) {

        }, "json")
            .done(function (data) {
                // inject returned html into page
                loc = data;

                $('#PayBankId').empty();

                $('#PayBankId').append($("<option></option>").attr("value",'').text('Нет'));
                $.each(loc,function(key, data){

//                    console.log(data);
                    $('#PayBankId').append($("<option></option>").attr("value",data.id).text(data.bank));
                });
            });
    });
    $('#PayOrgsId').change(function() {
//        PayBankId
        $.post('/ajax/org.php', {id_org: $(this).val()}, function (data) {

        }, "json")
            .done(function (data) {
                // inject returned html into page
                loc = data;

                $('#PayAccountsId').empty();
                $.each(loc,function(key, data){

//                    console.log(data);
                    $('#PayAccountsId').append($("<option></option>").attr("value",data.id).text(data.bank));
                });
            });
    });
        $(".chosen-select").chosen({
            no_results_text:"Совпадений не обнаружено",
            search_contains:true,
            placeholder_text_single:"Выберите значение",
            width: "100%"
        });

        $('.input_date').datetimepicker({defaultDate: new Date()/*, viewMode: 'years'*/,format: 'DD.MM.YYYY'});

    });
</script>