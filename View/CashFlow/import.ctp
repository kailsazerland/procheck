<?php ?>
<div class="row">
        <div id="breadcrumb" class="col-xs-12">
                <ol class="breadcrumb">
                        <li class="hidden-xs"><a href="#">К началу</a></li>
                        <li><a href="#">Движение денежных средств</a></li>
                        <li><a href="#">Импорт</a></li>
                </ol>
        </div>
</div>

<div class="col-xs-12 col-sm-6">
    <div class="panel panel-default">        
        <?php echo $this->Form->create('CashFlow', array('url' => './import_document', 'class' => 'form', 'role' => 'form','enctype' => 'multipart/form-data'));?>
        <div class="panel-heading">
            Импорт файла
        </div>
        <div class="panel-body">           
            <div class="input-group">
                <span class="input-group-btn">
                    <span class="btn btn-primary btn-file">
                        Выбрать… <?php echo $this->Form->file('submittedfile'); ?>
                    </span>
                </span>
                <input class="form-control" readonly="" type="text">
            </div>

</div> <div class="panel-footer">
            <?php

            echo '<div class="btn-group">' . $this->Form->submit('Выполнить',array('class' => 'btn btn-success','div' => false));
                    //. $this->Form->submit('Отмена',array('class' => 'btn btn-danger ajax-link','div' => false,'name' => 'cancel')) . '</div>';             
        ?>            
            
        </div>
        
        
        <?php echo $this->Form->end();?>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
       $(document).on('change', '.btn-file :file', function() {
            var input = $(this),
            numFiles = input.get(0).files ? input.get(0).files.length : 1,
            label = input.val().replace(/\\/g, '/').replace(/.*\//, '');                 
            input.trigger('fileselect', [numFiles, label]);
            $(this).parent().parent().siblings().val(label);
        });
    });
</script>
