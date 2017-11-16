<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=1,initial-scale=1,user-scalable=1" />

    <?php 
        echo $this->Html->meta('icon');    
        echo $this->fetch('meta'); 
    
    ?>

    <title><?php echo $this->fetch('title'); ?></title>

    <?php
        echo $this->Html->css('../lib/bootstrap-3.2.0/css/bootstrap.min.css');
        echo $this->Html->css('login.css');                
        echo $this->fetch('css');
    ?>
    
    <?php
        echo $this->Html->script(array('../lib/jquery/jquery.min.js'),array('inline' => true));
        echo $this->Html->script(array('../lib/bootstrap-3.2.0/js/bootstrap.min.js'),array('inline' => true));  
        echo $this->fetch('script');
    ?>    
		
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
    <div id="php-content"><?php echo $this->fetch('content'); ?></div>     
    <div id="my_msg"> <?php echo $this->element('message',array()); ?>   </div>    
    
    <?php
        echo $this->fetch('scriptAfter');
    ?>

</body>
</html>