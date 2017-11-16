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

<?
// echo "<pre>";
// print_r($file_id);
// echo "</pre>";
?>
<div class="col-xs-12 col-sm-6">
    <div class="panel panel-default">
        <div class="panel-heading">
            Добавление заявок 
        </div>
        <div class="panel-body">

        <?php
            echo $this->Form->create('Aunpaid', array('url' => './save', 'class' => 'form', 'role' => 'form','type'=>'file','enctype'=>'multipart/form-data'));
            echo $this->Form->input('Aunpaid.id',array('type' => 'hidden'));
            echo $this->Form->input('Aunpaid.user_id',array('type' => 'hidden', 'value'=> $auth));
            echo $this->Form->input('Aunpaid.type',array('type' => 'hidden', 'value'=> $type)); ?>

            <div class="input-group date">
                <?php echo $this->Form->input('Aunpaid.date',array('label' => false,'type' => 'hidden','div' => false,'wrap' => false,'class' => 'form-control', 'value' => date("Y-m-d H:i:s"))); ?>
            </div>


                <?php echo $this->Form->input('Aunpaid.orgs_id',array(
                                   'type' => 'select',
                                    'label' => 'Организация'
                                    ,'class' => 'chosen-select'
                                    ,'options' => $organiz
                                    ));?>

                <?php echo $this->Form->input('Aunpaid.money',array(
                                   'type' => 'number',
                                    'label' => 'Сумма'
                                    ,'class' => 'form-control'
                                    ,'step' => '0.01'
                                    ));?>

                <?php    echo $this->Form->input('Aunpaid.contragent_id',array(
                                   'type' => 'select',
                                    'label' => 'Контрагент'
                                    ,'class' => 'chosen-select'
                                    ,'options' => $contragents
                                    )); ?>

                <?php    echo $this->Form->input('Aunpaid.pay_type_id',array(
                                   'type' => 'select',
                                    'label' => 'Способ оплаты'
                                    ,'class' => 'chosen-select'
                                    ,'options' => $pay_types
                                    )); ?>

                <?php    echo $this->Form->input('Aunpaid.article_id',array(
                                   'type' => 'select',
                                    'label' => 'Статья'
                                    ,'class' => 'chosen-select'
                                    ,'options' => $stati
                                    )); ?>

                <?php    echo $this->Form->input('Aunpaid.classification_id',array(
                                   'type' => 'select',
                                    'label' => 'Классификация'
                                    ,'class' => 'chosen-select'
                                    ,'options' => $classif
                                    )); ?>

                <?php    echo $this->Form->input('Aunpaid.source_id',array(
                                   'type' => 'select',
                                    'label' => 'Источник'
                                    ,'class' => 'chosen-select'
                                    ,'options' => $istok
                                    )); ?>

               <div class="input-group date">
					<span class="input-group-addon">Срок оплаты</span>
					<?php echo $this->Form->input('Aunpaid.due_date',array('label' => false,'type' => 'text','div' => false,'wrap' => false,'class' => 'form-control input_date date')); ?>
				</div>

				<?php echo $this->Form->input('Aunpaid.note',array(
                                   'type' => 'textarea',
                                    'label' => 'Примечание'
                                    ,'class' => 'form-control'
                                    ));?>
                <?
                echo $this->Form->input('Aunpaid.files.', [
                        'label' => 'Файл',
                        'type' => 'file',
                        'multiple' => 'true',
                        'required'=> ''
                    ]
                );
                ?>


				
<?if($confirm_permitted){?>
				<?php echo $this->Form->input('Aunpaid.utver',array('label' => 'Утверждение','type' => 'checkbox', 'wrap' => false,'class' => 'form-control')); ?>
<?}?>
	



       <?php

            echo '<div class="btn-group">' . $this->Form->submit('Сохранить',array('class' => 'btn btn-success','div' => false))
                    . $this->Form->submit('Отмена',array('class' => 'btn btn-danger','div' => false,'name' => 'cancel')) . '</div>';
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