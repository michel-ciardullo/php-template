<?php

require dirname(__DIR__) . '/vendor/autoload.php';

use App\BaseView;
use App\ViewExtension;

$template = new BaseView(
    dirname(__DIR__) . '/views',
    dirname(__DIR__) . '/tmp'
);

$template->addExtension(new ViewExtension());

// Exemple de route basique
$uri = $_SERVER['REQUEST_URI'];

if ($uri === '/' || $uri === '/index.php') {
    echo $template->render('welcome');
} elseif ($uri === '/dashboard') {
    echo $template->render('dashboard', [
        'items' => ['php', 'blade', 'template']
    ]);
} else {
    http_response_code(404);
    echo "<h1>404 - Page non trouvée</h1>";
}
