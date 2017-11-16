<?php ?>
<div class="row">
        <div id="breadcrumb" class="col-xs-12">
                <ol class="breadcrumb">
                        <li class="hidden-xs"><a href="#">К началу</a></li>
                        <li><a href="#">Платежи</a></li>
                        <li><a href="#">Новые</a></li>
                        <li><a href="#">История изменений</a></li>
                </ol>
        </div>
</div>
<div class="container-fluid">
    <div class="navbar-btn navbar-left">
        <?php echo $this->Html->link('<i class="fa fa-arrow-left"></i>&nbspНазад','view/' . $this->data['Pay']['id'],array('class' => 'btn btn-primary ajax-link','escape' => false));?>
    </div>  
    <div class="navbar-form navbar-right"><h4><b>Платеж номер <?php echo $this->data['Pay']['id']; ?></b></h4></div>
        
</div>  


<table class='table table-hover table-striped table-bordered table-condensed'>
    <thead>
        <tr>
            <th>Дата</th>
            <th>Автор</th>
            <th>Действие</th>
        </tr>
    </thead>
    <?php
        foreach ($this->data['PayHistory'] AS $history)
        {
            echo $this->Html->TableCells(array(
                $this->Time->format('d.m.Y H:i:s',$history['modified'])
               ,$user[$history['user_id']]
               ,$type[$history['type']]
            ));
        }
    ?>
</table>


