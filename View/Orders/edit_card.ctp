<?php 
    //echo $this->Html->script(array('../lib/tinymce/tinymce.min.js'),array('inline' => true));
?>

<div class="modal fade" id="editCardModal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button class="close" type="button" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title text-primary" id="feature-title">Добавление/Редактирование</h4>
      </div>
      <div class="modal-body clearfix">

        <?php     
            echo $this->Form->create('OrderCard', array('url' => './save_card', 'class' => 'form', 'role' => 'form'));		
            echo $this->Form->input('OrderCard.id',array('type' => 'hidden')); 
            echo $this->Form->input('OrderCard.order_id',array('type' => 'hidden', 'value' => $this->data['Order']['id'])); 
            echo $this->Form->input('OrderCard.article_id',array(
                                   'type' => 'select',
                                    'label' => 'Статья'
                                    ,'class' => 'chosen-select'
                                    ,'options' => $articles
                                    ));
            echo $this->Form->input('OrderCard.contragent_id',array(
                                   'type' => 'select',
                                    'label' => 'Контрагент'
                                    ,'class' => 'chosen-select'
                                    ,'options' => $contragents
                                    ));            
            echo $this->Form->input('OrderCard.cash',array('label' => 'Сумма'));            
            echo $this->Form->input('OrderCard.nalog_id',array(
                                   'type' => 'select',
                                    'label' => 'НДС'
                                    ,'class' => 'chosen-select'
                                    ,'options' => $nalogs
                                    ));            
            echo $this->Form->input('OrderCard.pay_type_id',array(
                                   'type' => 'select',
                                    'label' => 'Способ оплаты'
                                    ,'class' => 'chosen-select'
                                    ,'options' => $pay_types
                                    ));            
            echo $this->Form->input('OrderCard.about',array('label' => 'Описание'));
            echo '<div class="btn-group pull-right">' . $this->Form->submit('Сохранить',array('class' => 'btn btn-success ajax-link close-dialog','div' => false, 'data-dismiss' => 'modal')) . '</div>'; 
            echo $this->Form->end();
        ?>    
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
    
<?php 
    echo $this->Html->scriptBlock('$("#editCardModal").modal("show").on("shown.bs.modal", function (){});');
?>

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
