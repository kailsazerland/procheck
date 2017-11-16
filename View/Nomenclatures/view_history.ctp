<?php ?>
<div class="row">
        <div id="breadcrumb" class="col-xs-12">
                <ol class="breadcrumb">
                        <li class="hidden-xs"><a href="#">К началу</a></li>
                        <li><a href="#">Справочники</a></li>
                        <li><a href="#">Номенклатура</a></li>
                        <li><a href="#">История изменений</a></li>
                </ol>
        </div>
</div>


<div class="container-fluid">
    <div class="navbar-btn navbar-left">
        <?php echo $this->Html->link('<i class="fa fa-arrow-left"></i>&nbspНазад','view/' . $this->data['Nomenclature']['type_nomenclature_id'],array('class' => 'btn btn-primary ajax-link','escape' => false));?>
    </div>  
    <div class="navbar-form navbar-right"><h4><b><?php echo $this->data['Nomenclature']['name']; ?></b></h4></div>
        
</div>  


<table class='table table-hover table-striped table-bordered table-condensed'>
    <thead>
        <tr>
            <th>Дата изменения</th>            
            <th>Себестоимость</th>            
        </tr>
    </thead>
    <?php
        foreach ($this->data['NomenclatureHistory'] AS $history)
        {
            echo $this->Html->TableCells(array(
                $this->Time->format('d.m.Y',$history['created'])
               ,$history['price']                       
            ));
        }
    ?>
</table>


