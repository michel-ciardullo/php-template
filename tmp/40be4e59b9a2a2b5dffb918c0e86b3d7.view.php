<?php $_layout = 'layouts.default'; ?>

<?php $this->section('head', function($args) { extract($args); ?>
    <title>Home | The Monster [Dev]</title>
<?php }); ?>

<?php $this->section('content', function($args) { extract($args); ?>
    <h2>Hello World</h2>
<?php }); ?>

<?php $this->section('footer', function($args) { extract($args); ?>

<?php }); ?>
