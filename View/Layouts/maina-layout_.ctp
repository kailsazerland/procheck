<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1,user-scalable=no,maximum-scale=1,width=device-width">
    
    <?php 
        echo $this->Html->meta('icon');    
        echo $this->fetch('meta'); 
    ?>
    
    <title><?php /*echo $this->fetch('title');*/ echo 'Ставтрэк "Финансы"' ?></title>

    <?php
        echo $this->Html->css('../lib/bootstrap-3.2.0/css/bootstrap.css');
        echo $this->Html->css('../lib/bootstrap-slider/slider.css');
        
        
        echo $this->Html->css('../lib/font-awesome-4.4.0/css/font-awesome.min.css');
        //echo $this->Html->css('../lib/jquery.webui-popover/jquery.webui-popover.min.css'); 
        echo $this->Html->css('../lib/chosen/bootstrap-chosen.css');
        echo $this->Html->css('../lib/bootstrap3-editable/css/bootstrap-editable.css');
        echo $this->Html->css('../lib/jquery-ui/jquery-ui.min.css');
        
        //echo $this->Html->css('../lib/jquery.scrollbar/jquery.scrollbar.css');
        echo $this->Html->css('ganti-css/gantti.css');
        echo $this->Html->css('../lib/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css');
        echo $this->Html->css('led-icons.min.css');
        echo $this->Html->css('app.css');
        echo $this->fetch('css');
    ?>
    
  </head>

  <body>
    <header class="navbar">
        <div class="container-fluid expanded-panel">
            <div class="row">
                <div id="logo" class="col-xs-12 col-sm-2">
                    <?php echo $this->Html->image('stavtrack.png',array('class' => 'img-responsive', 'alt' => 'StavTrack')) ?>
                </div>
                <div id="top-panel" class="col-xs-12 col-sm-10">
                    <div class="row">
                        <div class="col-xs-8 col-sm-4">
                            <a href="#" class="show-sidebar">
                                <i class="fa fa-bars"></i>
                            </a>
                        </div>
                        <div class="col-xs-4 col-sm-8 top-panel-right">
                            <ul class="nav navbar-nav pull-right panel-menu">
                                <li class="hidden-xs">
                                        <a href="#">
                                                <i class="fa fa-bell"></i>
                                                <span class="badge">0</span>
                                        </a>
                                </li>
                                <!--<li class="hidden-xs">
                                        <a href="#">
                                                <i class="fa fa-envelope"></i>
                                                <span class="badge">0</span>
                                        </a>
                                </li>-->
                                <li class="dropdown top-panel-menu">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                            <i class="fa fa-angle-down pull-right"></i>
                                            <span><?php echo $Auth['username'];?></span>
                                    </a>
                                    <ul class="dropdown-menu">
                                            <li>
                                                    <a href="#">
                                                            <i class="fa fa-user"></i>
                                                            <span>Профиль</span>
                                                    </a>
                                            </li>
                                            <li>
                                                    <a href="../users/logout">
                                                            <i class="fa fa-power-off"></i>
                                                            <span>Выход</span>
                                                    </a>
                                            </li>
                                    </ul>
                                </li>
                            </ul>    
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <div id="main" class="container-fluid">
        <div class="row">
            <div id="sidebar-left" class="col-xs-2 col-sm-2">
                <?php echo $this->Menu->DrawMenu($main_menu,array('class' => 'nav main-menu')); ?> 
                <div class="navbar-text">
                    © stavtrack 2016
                </div>                
            </div>
            <div id="content" class="col-xs-12 col-sm-10">
                <div class="ajax-preloader">
                    <?php echo $this->Html->image('preloader.gif',array('class' => 'devoops-getdata', 'alt' => 'preloader')) ?>
                </div>
                <div id="ajax-content">
                    <?php echo $this->fetch('content'); ?>                            
                </div>                                       
            </div>             
        </div>
    </div>  
      
    <div id="my-dialog"> </div> 
          
    <div id="my-message"> <?php echo $this->element('message',array()); ?>   </div> 

    <?php
        echo $this->Html->script(array('../lib/jquery/jquery.min.js'),array('inline' => true));
        
        //echo $this->Html->script(array('../lib/jquery.scrollbar/jquery.scrollbar.min.js'),array('inline' => true));
        echo $this->Html->script(array('../lib/jquery-ui/jquery-ui.min.js'),array('inline' => true));
        //echo $this->Html->script(array('../lib/jquery-ui/i18n/jquery.ui.datepicker-ru.min.js'),array('inline' => true));
        echo $this->Html->script(array('../lib/moment/moment.min.js'),array('inline' => true));
        echo $this->Html->script(array('../lib/bootstrap-3.2.0/js/bootstrap.min.js'),array('inline' => true));       
        //echo $this->Html->script(array('../lib/jquery.webui-popover/jquery.webui-popover.min.js'),array('inline' => true));        
        echo $this->Html->script(array('../lib/chosen/chosen.jquery.min.js'),array('inline' => true));
        echo $this->Html->script(array('../lib/moment/locale/ru.js'),array('inline' => true));
        echo $this->Html->script(array('../lib/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js'),array('inline' => true));
        
        echo $this->Html->script(array('../lib/bootstrap-slider/bootstrap-slider.js'),array('inline' => true));
        
        echo $this->fetch('script');
        
        echo $this->Html->script(array('app.js'),array('inline' => true));
        echo $this->fetch('scriptAfter');
        //echo $this->Html->scriptBlock('var cake_base_url = "' . Router::url($this->here,true) . '"');
        echo $this->Html->scriptBlock('var cake_base_url = ' . $this->Html->url('/'));         
    ?>
        
  </body>
</html>
