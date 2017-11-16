<?php ?>
<div class="row">
        <div id="breadcrumb" class="col-xs-12">
                <ol class="breadcrumb">
                        <li class="hidden-xs"><a href="#">К началу</a></li>
                        <li><a href="#">Справочники</a></li>
                        <li><a href="#">Статьи доходов и расходов</a></li>
                        <li><a href="#">Добавить</a></li>
                </ol>
        </div>
</div>

<div class="col-xs-12 col-sm-6">
    <div class="panel panel-default">
        <div class="panel-heading">
            Добавление статьи (доходов/расходов)
        </div>
        <div class="panel-body">

        <?php
            echo $this->Form->create('Article', array('url' => './save', 'class' => 'form', 'role' => 'form'));
            echo $this->Form->input('Article.id',array('type' => 'hidden'));
            echo $this->Form->input('Article.name',array('label' => 'Наименование'));
            echo $this->Form->input('Article.type_id',array(
                                   'type' => 'select',
                                    'label' => 'Тип'
                                    ,'class' => 'chosen-select chosen-change'
                                    ,'options' => $this->data['Article']['Types']
                                    ));
            /*echo $this->Form->input('Article.contragent_id',array(
                                   'type' => 'select',
                                    'label' => 'Контрагент'
                                    ,'class' => 'chosen-select'
                                    ,'options' => $this->data['Article']['Contragents']
                                    )); */           
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