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

<div class="col-xs-12 col-sm-12">
    <div class="panel panel-default">        
        <div class="panel-heading">
            Результат обработки файла
        </div>
        <div class="panel-body">           
            <p><b>обработан файл - </b><?php echo $file_name;?></p>
            <p><b>найдено документов - </b><?php echo $count_docs;?></p>
            <p><b>загруженно документов - </b><?php echo $import_docs;?></p>  
        </div> 
        <div class="panel-footer">
            <?php echo $this->Html->link('<i class="fa fa-arrow-left"></i>&nbspНазад','import',array('class' => 'btn btn-primary ajax-link','escape' => false));?>
        </div>

    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {

    });
</script>
