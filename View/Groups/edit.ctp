<?php ?>
<div class="row">
        <div id="breadcrumb" class="col-xs-12">
                <ol class="breadcrumb">
                        <li class="hidden-xs"><a href="#">К началу</a></li>
                        <li><a href="#">Настройки</a></li>
                        <li><a href="#">Настройки групп прав</a></li>
                        <li><a href="#">Добавить</a></li>
                </ol>
        </div>
</div>

<div class="col-xs-12 col-sm-6">
    <div class="panel panel-default">
        <div class="panel-heading">
            Добавление / редактирование групп прав
        </div>
        <div class="panel-body">

        <?php
            echo $this->Form->create('Group', array('url' => './save', 'class' => 'form', 'role' => 'form'));		
            echo $this->Form->input('Group.id',array('type' => 'hidden')); 
            echo $this->Form->input('Group.name',array('label' => 'Наименование'));  
            echo $this->Form->input('Permission.Permission',array(
                       'type' => 'select'
                        ,'label' => 'Права'
                        ,'data-placeholder' => 'Добавте права ...'
                        ,'class' => 'chosen-select chosen-change'
                        ,'multiple' => true
                        ,'options' => $this->data['Group']['Permissions']
                        )); 
            echo $this->Form->input('Group.about',array('label' => 'Описание','type' => 'textarea'));              
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