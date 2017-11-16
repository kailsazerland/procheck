<?php ?>
<div class="row">
        <div id="breadcrumb" class="col-xs-12">
                <ol class="breadcrumb">
                        <li class="hidden-xs"><a href="#">К началу</a></li>
                        <li><a href="#">Бюджетирование</a></li>
                        <li><a href="#">План/факт анализ</a></li>
                        <li><a href="#">Бюджет доходов</a></li>
                </ol>
        </div>
</div>

<div class="container-fluid">
<div class="navbar-btn navbar-left page-header">
    <?php echo $this->Html->link('Копировать последнее','copy_budget_in',array('class' => 'btn btn-success ajax-link'));?>
    <?php echo $this->Html->link('Добавить','add_budget_in',array('class' => 'btn btn-success ajax-link'));?>
</div>
    
<?php echo $this->element('search',array('search_view' => 'view_budget_in')); ?>   
</div>

<?php
    foreach ($budgets AS $value)
    {
?>

    <div class="col-sm-6 col-md-3">
      <div class="panel panel-default">
        <div class="panel-heading clearfix">
            <div class="row">
            <div class="col-sm-12 col-md-3">
                <small class="stat-label">Период:</small>
            </div>
            <div class="col-sm-12 col-md-9">
                <h4 class="pull-right"><b><?php echo __d('cake',$this->Time->format('F',$value['Budget']['period'])) . ' ' . $this->Time->format('Y',$value['Budget']['period']); ?></b></h4>
            </div>
            </div>
            <div class="row">
            <div class="col-sm-12 col-md-6">
                <small class="stat-label">Итого (план):</small>
            </div>
            <div class="col-sm-12 col-md-6">
                <h5 class="pull-right"><b><?php  echo $this->Number->currency($value['Budget']['cash_plan'],''); ?></b></h5>
            </div>
            </div>
            <div class="row">
            <div class="col-sm-12 col-md-6">
                <small class="stat-label">Итого (факт):</small>
            </div>
            <div class="col-sm-12 col-md-6">
                <h5 class="pull-right"><b><?php  echo $this->Number->currency($value['Budget']['cash_fact'],''); ?></b></h5>
            </div>
            </div>
            <div class="row">
            <div class="col-sm-12 col-md-6">
                <small class="stat-label">Разница план/факт:</small>
            </div>
            <div class="col-sm-12 col-md-6">
                <h4 class="pull-right"><?php  $difference = $this->Number->currency($value['Budget']['difference'],'',array('negative' => '-')); 
                                              if($value['Budget']['difference'] < 0) $difference = '<b class="text-danger">' . $difference . '</b>';
                                              else $difference = '<b>' . $difference . '</b>';
                                              echo $difference;
                                        ?></h4>
            </div>             
            </div>
        </div><!-- panel-heading -->
        <div class="panel-footer clearfix">
            <div class="btn-group pull-right">
                <?php echo $this->ActionPanel->DrawPanel($action_menu,$value['Budget']['id']) ?>
            </div>
        </div>
      </div><!-- panel -->
    </div>

<?php
    }
?> 
