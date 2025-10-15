<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="template">
        <meta name="author" content="ToToRiina">

        <?= $this->yield('head', $args); ?>
    </head>
    <body>
        <?= $this->yield('content', $args); ?>
        <?= $this->yield('footer', $args); ?>
    </body>
</html>