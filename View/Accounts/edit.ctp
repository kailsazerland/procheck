<?php ?>
<div class="row">
        <div id="breadcrumb" class="col-xs-12">
                <ol class="breadcrumb">
                        <li class="hidden-xs"><a href="#">К началу</a></li>
                        <li><a href="#">Справочники</a></li>
                        <li><a href="#">Счета</a></li>
                        <li><a href="#"><?php echo $org['Org']['name'];?></a></li>
                        <li><a href="#">Добавить</a></li>
                </ol>
        </div>
</div>

<div class="col-xs-12 col-sm-6">
    <div class="panel panel-default">
        <div class="panel-heading">
            Добавление счетов
        </div>
        <div class="panel-body">

        <?php
            echo $this->Form->create('Account', array('url' => './save', 'class' => 'form', 'role' => 'form'));		
            echo $this->Form->input('Account.id',array('type' => 'hidden')); 
            echo $this->Form->input('Account.org_id',array('type' => 'hidden'));
            echo $this->Form->input('Account.bank',array('label' => 'Наименование банка'));
            echo $this->Form->input('Account.account',array('label' => 'счет'));           
            echo $this->Form->input('Account.city_bank',array('label' => 'Город банка'));
            echo $this->Form->input('Account.bik',array('label' => 'БИК'));
            echo $this->Form->input('Account.kors',array('label' => 'Корсчет'));
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