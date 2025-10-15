<?php

require dirname(__DIR__) . '/vendor/autoload.php';

$template = new \PhpTemplate\BaseView(
    dirname(__DIR__) . '/views',
    dirname(__DIR__) . '/tmp'
);

echo $template->render('welcome');
