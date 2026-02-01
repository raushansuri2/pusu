<!DOCTYPE html>
<html>
<head>
    <!-- Fixed: Use array for options in Url->build() -->
    <link rel="icon" type="image/png" sizes="96x96" href="<?= $this->Url->build('/favcon.ico', ['fullBase' => true]) ?>">
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">    
    <title><?= $layoutTitle ?></title>    
    <?php
    if (isset($description_for_layout)) {
        echo $this->Html->meta('description', $description_for_layout);
    }
    if (isset($keywords_for_layout)) {
        echo $this->Html->meta('keywords', $keywords_for_layout);
    } 
    echo $this->Html->css([
        'admin/bootstrap.min',
        'admin/bootstrap-override', 
        'admin/weather-icons.min',
        'admin/jquery-ui-1.10.3.css',
        'admin/font-awesome.min',
        'admin/animate.min',
        'admin/animate.delay',
        'admin/toggles',
        'admin/pace',
        'admin/style.default', 
        'admin/custom'
    ]);
    ?>
</head>
<body class="signin">
    <section>
        <?= $this->fetch('content') ?>
    </section>
    <?php 
    echo $this->Html->script([
        'admin/jquery-1.11.1.min', 
        'admin/jquery-migrate-1.2.1.min',
        'admin/jquery-ui-1.10.3.min',
        'admin/bootstrap.min',
        'admin/modernizr.min',
        'admin/pace.min',
        'admin/retina.min',
        'admin/jquery.cookies',
        'admin/select2.min'                
    ]);
    echo $this->Html->script(['admin/jquery.validate.min', 'admin/custom']);
    ?>
    <script>
        jQuery(document).ready(function(){
            jQuery("#basicForm").validate({});
        });
    </script>
</body>
</html>