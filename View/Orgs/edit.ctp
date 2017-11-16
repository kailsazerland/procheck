<?php ?>
<div class="row">
        <div id="breadcrumb" class="col-xs-12">
                <ol class="breadcrumb">
                        <li class="hidden-xs"><a href="#">К началу</a></li>
                        <li><a href="#">Справочники</a></li>
                        <li><a href="#">Организации</a></li>
                        <li><a href="#">Добавить</a></li>
                </ol>
        </div>
</div>

<div class="col-xs-12 col-sm-6">
    <div class="panel panel-default">
        <div class="panel-heading">
            Добавление организаций
        </div>
        <div class="panel-body">

        <?php
            echo $this->Form->create('Org', array('url' => './save', 'class' => 'form', 'role' => 'form'));		
            echo $this->Form->input('Org.id',array('type' => 'hidden')); 
            echo $this->Form->input('Org.orgs_group_id',array('type' => 'hidden'));             
            echo $this->Form->input('Org.name',array('label' => 'Наименование'));           
            echo $this->Form->input('Org.inn',array('label' => 'Инн'));
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