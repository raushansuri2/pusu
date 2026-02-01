<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
<meta name="description" content="<?= h($description_for_layout ?? '') ?>">
<meta name="author" content="">
<title><?= h($layoutTitle) ?></title>

<?php
// Meta tags for description and keywords if set
if (isset($keywords_for_layout)) {
    echo $this->Html->meta('keywords', h($keywords_for_layout));
}

// Favicon with correct path and options
echo $this->Html->meta(
    ['rel' => 'icon', 'type' => 'image/x-icon'],
    $this->Url->build('/img/favicon.ico', ['fullBase' => true])
);

// CSS files
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
    'admin/morris',
    'admin/select2',
    'admin/custom'
]);

// JS files
echo $this->Html->script([
    'admin/jquery-1.11.1.min',
    'admin/jquery-migrate-1.2.1.min',
    'admin/bootstrap.min',
    'admin/modernizr.min',
    'admin/pace.min',
    'admin/retina.min',
    'admin/jquery.cookies',
    'admin/select2.min'
]);
?>

<!-- HTML5 shim and Respond.js for IE8 support -->
<!--[if lt IE 9]>
    <script src="<?= $this->Url->build('/js/html5shiv.js') ?>"></script>
    <script src="<?= $this->Url->build('/js/respond.min.js') ?>"></script>
<![endif]-->