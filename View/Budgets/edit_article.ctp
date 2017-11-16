<?php 
    //echo $this->Html->script(array('../lib/tinymce/tinymce.min.js'),array('inline' => true));
?>

<div class="modal fade" id="editArticleModal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button class="close" type="button" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title text-primary" id="feature-title">Добавление/Редактирование</h4>
      </div>
      <div class="modal-body clearfix">
        <?php         
            echo $this->Form->create('Budget', array('url' => './save_article_budget', 'class' => 'form', 'role' => 'form'));		
            echo $this->Form->input('Budget.id',array('type' => 'hidden')); 
            echo $this->Form->input('Budget.period',array('type' => 'hidden')); 
            echo $this->Form->input('Budget.type_id',array('type' => 'hidden'));
            echo $this->Form->input('BudgetValues.id',array('type' => 'hidden')); 
            echo $this->Form->input('BudgetValues.article_id',array(
                                   'type' => 'select',
                                    'label' => 'Статья'
                                    ,'class' => 'chosen-select'
                                    ,'options' => $articles
                                    ));
            echo $this->Form->input('BudgetValues.cash_plan',array('label' => 'Сумма денежных средств (план.)'));
            echo $this->Form->input('BudgetValues.cash_fact',array('label' => 'Сумма денежных средств (факт.)'));
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
    echo $this->Html->scriptBlock('$("#editArticleModal").modal("show").on("shown.bs.modal", function (){});');
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
