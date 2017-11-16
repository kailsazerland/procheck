<?php ?>
<div class="row">
        <div id="breadcrumb" class="col-xs-12">
                <ol class="breadcrumb">
                        <li class="hidden-xs"><a href="#">К началу</a></li>
                        <li><a href="#">Бюджетирование</a></li>
                        <li><a href="#">Платежный календарь</a></li>                      
                        <li><a href="#"><?php echo $calendar['Calendar']['name']; ?></a></li>
                </ol>
        </div>
</div>

<div class="container-fluid">
        <div class="navbar-btn navbar-left">
            <?php echo $this->Html->link('<i class="fa fa-arrow-left"></i>&nbspНазад','view_calenars',array('class' => 'btn btn-primary ajax-link','escape' => false));?>
        </div>            
</div>  

<div class="col-xs-12 col-sm-12">
    <?php echo $this->Ganti->DrawDiagramm('Статья',$data); ?>
</div>

<script type="text/javascript">
    $(document).ready(function() {
    
    });
</script>