<!DOCTYPE html>
<html>
    <head>
        <?php echo $this->element('head');?>
    </head>
    <?php
    $action = $this->request->params['action'];
    $class= '';
    if(in_array($action, array('connectme', 'index'))){
        $class= '';
    }elseif(in_array($action, array('profile', 'searchlist'))){
        $class = 'class= "bg-gray"';
    }else{
        $class = 'class= "inner-gradient"';
    }
    ?>
    <body <?php echo $class;?>>
        <!-- wrapper -->
        <div class="wrapper inner">
            <header>
                <?php echo $this->element('header-inner');?>
            </header>
            <div class="cl"></div>
            
                <?php echo $this->fetch('content'); ?>
            

        </div>
        <!-- wrapper end --> 
    </body>
</html>
