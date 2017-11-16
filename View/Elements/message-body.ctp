<div class="modal fade" id="dialogModal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content <?php echo (isset($class))?$class:'panel-danger'; ?>">
      <div class="modal-header panel-heading">
        <button class="close" type="button" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title text-primary" >Сообщение</h4>
      </div>
      <div class="modal-body" ><?php echo (isset($message))?$message:$this->Session->flash();?></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->    

<?php 

        echo $this->Html->scriptBlock('
            $(document).ready(function () {
                $("#dialogModal").modal("show");
            });
        '); 

?>