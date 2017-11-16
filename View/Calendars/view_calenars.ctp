<?php ?>
<div class="row">
        <div id="breadcrumb" class="col-xs-12">
                <ol class="breadcrumb">
                        <li class="hidden-xs"><a href="#">К началу</a></li>
                        <li><a href="#">Бюджетирование</a></li>
                        <li><a href="#">Платежный календарь</a></li>
                </ol>
        </div>
</div>

<div class="container-fluid">
<div class="navbar-btn navbar-left page-header">
    <?php echo $this->Html->link('Сформировать','preview_make',array('class' => 'btn btn-success ajax-link'));?>
</div>
    

</div>

<?php
    foreach ($calenars AS $value)
    {
?>

    <div class="col-sm-6 col-md-3">
      <div class="panel panel-default">
        <div class="panel-heading clearfix">
            <div class="row">
            <div class="col-sm-12 col-md-12">
                <small class="stat-label"><b><?php  echo $value['Calendar']['name']; ?></b></small>
            </div>
            </div>
            
            <div class="row">
            <div class="col-sm-12 col-md-3">
                <small class="stat-label">Период:</small>
            </div>
            <div class="col-sm-12 col-md-9">
                <h4 class="pull-right"><b><?php echo __d('cake',$this->Time->format('F',$value['Calendar']['period'])) 
                        . ' ' . $this->Time->format('Y',$value['Calendar']['period']); ?></b></h4>
            </div>
            </div>          
          
        </div><!-- panel-heading -->
        <div class="panel-footer clearfix">
            <div class="btn-group pull-right">
                <?php echo $this->ActionPanel->DrawPanel($action_menu,$value['Calendar']['id']) ?>
            </div>
        </div>
      </div><!-- panel -->
    </div>

<?php
    }
?> 
