<?php ?>
<div class="row">
        <div id="breadcrumb" class="col-xs-12">
                <ol class="breadcrumb">
                        <li class="hidden-xs"><a href="#">К началу</a></li>
                        <li><a href="#">Настройки</a></li>
                        <li><a href="#">Права доступа</a></li>
                        <li><a href="#">Добавить</a></li>
                </ol>
        </div>
</div>

<div class="col-xs-12 col-sm-6">
    <div class="panel panel-default">
        <div class="panel-heading">
            Добавление / редактирование прав доступа
        </div>
        <div class="panel-body">

        <?php
            echo $this->Form->create('Permission', array('url' => './save', 'class' => 'form', 'role' => 'form'));		
            echo $this->Form->input('Permission.id',array('type' => 'hidden')); 
            echo $this->Form->input('Permission.name',array('label' => 'Наименование'));  
            echo $this->Form->input('Permission.action',array('label' => 'Действие'));      
            echo $this->Form->input('Permission.about',array('label' => 'Описание','type' => 'textarea'));  
        ?>    
            <div class="form-group">
                <label for="PermissionPermission" class="control-label">является группой</label>
                <div class="toggle-switch toggle-switch-success">              
                    <label>
                        <?php echo $this->Form->checkbox('Permission.is_group',array('label' => 'Группа')); ?>
                        <div class="toggle-switch-inner"></div>
                        <div class="toggle-switch-switch"><i class="fa fa-check"></i></div>
                    </label>            
                </div>
             </div>
        <?php            
            /*echo $this->Form->input('PermissionChildrens.PermissionChildrens',array(
                       'type' => 'select'
                        ,'label' => 'Права'
                        ,'data-placeholder' => 'Добавте права ...'
                        ,'class' => 'chosen-select chosen-change'
                        ,'multiple' => true
                        ,'options' => $this->data['Permission']['Permissions']
                        )); */
            
            echo '<div class="btn-group">' . $this->Form->submit('Сохранить',array('class' => 'btn btn-success','div' => false)) 
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