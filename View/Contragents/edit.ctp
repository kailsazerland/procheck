<?php ?>
<div class="row">
        <div id="breadcrumb" class="col-xs-12">
                <ol class="breadcrumb">
                        <li class="hidden-xs"><a href="#">К началу</a></li>
                        <li><a href="#">Справочники</a></li>
                        <li><a href="#">Контрагенты</a></li>
                        <li><a href="#">Добавить</a></li>
                </ol>
        </div>
</div>

<div class="col-xs-12 col-sm-6">
    <div class="panel panel-default">
        <div class="panel-heading">
            Добавление контрагента
        </div>
        <div class="panel-body">

        <?php
            echo $this->Form->create('Conragent', array('url' => './save', 'class' => 'form', 'role' => 'form'));		
            echo $this->Form->input('Contragent.id',array('type' => 'hidden')); 
            echo $this->Form->input('Contragent.name',array('label' => 'Имя'));
            echo $this->Form->input('Contragent.inn',array('label' => 'ИНН'));
            echo $this->Form->input('Contragent.orgs_group_id',array(
                                   'type' => 'select',
                                    'label' => 'Тип контрагента'
                                    ,'class' => 'chosen-select'
                                    ,'options' => $this->data['Contragent']['OrgsGroup']
                                    ));
            /*echo $this->Form->input('Contragent.type_id',array(
                                   'type' => 'select',
                                    'label' => 'Тип'
                                    ,'class' => 'chosen-select chosen-change'
                                    ,'options' => $this->data['Contragent']['Types']
                                    ));*/        
            echo $this->Form->input('Account.Account.bank',array('label' => 'Наименование банка'));
            echo $this->Form->input('Account.Account.account',array('label' => 'Номер расчетного счета'));
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