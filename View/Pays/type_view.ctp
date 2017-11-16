<?php ?>
<div class="row">
        <div id="breadcrumb" class="col-xs-12">
                <ol class="breadcrumb">
                    <li class="hidden-xs"><a href="#">К началу</a></li>
                    <li><a href="#">Платежи</a></li>
                    <li><a href="#"><?=$title?></a></li>
                </ol>
        </div>
</div>

<div class="container-fluid">
        <div class="navbar-btn navbar-left">
            <?php echo $this->Html->link('Добавить','/orgs/add',array('class' => 'btn btn-success ajax-link'));?>
        </div>            
</div>  
           
<?php
    foreach ($orgs AS $value)
    {
?>
    <div class="col-sm-6 col-md-3">
      <div class="panel panel-default">
        <div class="panel-heading clearfix">
            <?php echo $this->Html->link($value['Orgs']['name']. '('.$count[$value['Orgs']['id']].')',$page.'/' . $value['Orgs']['id'],array('class' => 'btn btn-default ajax-link'));?>
        </div><!-- panel-heading -->
      </div><!-- panel -->
    </div>

<?php
    }
?> 


