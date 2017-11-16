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
        <div class="navbar-btn navbar-left">
            <?php echo $this->Html->link('<i class="fa fa-arrow-left"></i>&nbspНазад','filter',array('class' => 'btn btn-primary ajax-link','escape' => false));?>
        </div>            
</div>  

<div class="col-xs-12 col-sm-12">
    <?php 
        if($type == 'chart')
            echo $this->Ganti->DrawDiagramm('Статья',$data); 
        else {
    ?>

    <table class='table table-hover table-striped table-bordered table-condensed'>
        <thead>
            <tr>
                <th>Статья</th>
                <th>Даты</th>
                <!--<th>Контрагент</th>-->
                <th>Сумма</th>
            </tr>
        </thead>
        <?php
            foreach ($data AS $value)
            {
                echo $this->Html->TableCells(array(
                     $value['label']
                    ,'с ' . $this->Time->format('d.m.Y',$value['start']) . ' -  по ' . $this->Time->format('d.m.Y',$value['end'])
                        
                    //,$value['org']
                    ,$value['info']
                ));
            }
        ?>    
    </table>            
            
            
    <?php       
        }
    ?>
</div>

<script type="text/javascript">
    $(document).ready(function() {
    
    });
</script>