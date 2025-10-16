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
        <?= $this->render('partials.navbar') ?>

        <main>
            <?= $this->yield('content', $args); ?>
        </main>

        <?= $this->yield('footer', $args); ?>

        <footer>
            <p>&copy; 2025 - Company</p>
            <?= $this->yield('footer', $args); ?>
            <?= $this->stack('scripts', $args); ?>
        </footer>
    </body>
</html>
