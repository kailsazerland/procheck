<?php ?>
<div class="modal fade" id="dialogEditContragent" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button class="close" type="button" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title text-primary" id="feature-title">Добавление/редактирование контрагента</h4>
      </div>
      <div class="modal-body clearfix">

          
        <?php
            echo $this->Form->create('Conragent', array('url' => './save_dialog', 'class' => 'form', 'role' => 'form'));		
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
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
    
<script type="text/javascript">
    $(document).ready(function() {
        $("#dialogEditContragent").modal({backdrop: 'static', keyboard: false}).on("shown.bs.modal", function (){})
        .on("hide.bs.modal", function (){
            $(this).remove();
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