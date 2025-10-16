<?php $_layout = 'layouts.default'; ?>

<?php $this->section('title', function($args) { extract($args); ?>
    Bienvenue
<?php }); ?>

<?php $this->section('content', function($args) { extract($args); ?>
    <h1>👋 Hello World!</h1>
    <p>Bienvenue sur le moteur de template <strong>php-template</strong>.</p>

    <?php $this->push('scripts', function($args) { extract($args); ?>
        <script>console.log('Script depuis welcome.view');</script>
    <?php }); ?>
<?php }); ?>
