# 🧩 php-template

**php-template** est un micro moteur de template PHP inspiré de [Blade](https://laravel.com/docs/blade) (Laravel).  
Il permet d’organiser et de séparer la logique de présentation dans vos applications PHP tout en restant léger, sans dépendance lourde ni framework complet.

---

## 🚀 Installation

1. **Cloner le dépôt :**

```bash
git clone https://github.com/ton-utilisateur/php-template.git
cd php-template
```

2. **Installer les dépendances :**

```bash
composer install
```

3. **Lancer un serveur local :**

```bash
php -S localhost:8000 -t public
```

4. **Ouvrir dans votre navigateur :**

```bash
http://localhost:8000
```

## 🧱 Project Architecture

```
📦 php-template
┣ 📂 public/               → Dossier public (point d’entrée du serveur)
┃ ┗ 📜 index.php           → Page d’accueil (exécution principale)
┣ 📂 src/                  → Code source du moteur de template
┃ ┣ 📂 Exception/          → Gestion des exceptions personnalisées
┃ ┣ 📜 BaseView.php        → Classe de base pour le rendu des vues
┃ ┗ 📜 ViewExtension.php   → Extensions et helpers pour les vues
┣ 📂 tmp/                  → Fichiers temporaires générés lors du rendu
┣ 📂 vendor/               → Librairies installées via Composer
┣ 📂 views/                → Templates PHP (similaire à Blade de Laravel)
┃ ┣ 📂 layouts/            → Layouts globaux (ex : `default.view`)
┃ ┗ 📜 welcome.view        → Exemple de vue simple
┣ 🧩 .gitignore            → Fichiers à ignorer par Git
┣ 🧩 .gitattributes        → Configuration Git (fin de lignes, linguist, etc.)
┣ ⚙️ composer.json         → Dépendances et configuration Composer
┣ 🧩 LICENSE               → Licence du projet
┗ 🧾 README.md             → Documentation du projet
```

## 🧠 Exemple minimal (public/index.php)

```php
<?php

require dirname(__DIR__) . '/vendor/autoload.php';

use PhpTemplate\BaseView;

$template = new BaseView(
    dirname(__DIR__) . '/views', // dossier des vues
    dirname(__DIR__) . '/tmp'    // dossier de cache
);

echo $template->render('welcome');
```

## 🧩 Exemple de Layout (views/layouts/default.view)

```html
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="template">
        <meta name="author" content="ToToRiina">

        @yield('head')
    </head>
    <body>
        @yield('content')
        @yield('footer')
    </body>
</html>
```

## 🪄 Exemple de Vue (views/welcome.view)

```html
@extend('layouts.default')

@section('head')
    <title>Home | The Monster [Dev]</title>
@endsection

@section('content')
    <h2>Hello World 👋</h2>
    <p>Bienvenue sur votre moteur de template PHP.</p>
@endsection

@section('footer')
    <footer>
        <p>&copy; 2025 The Monster Dev</p>
    </footer>
@endsection
```

## ⚙️ Exemple avancé — Injection de variables

```php
// public/index.php
$data = [
    'title' => 'Dashboard',
    'user'  => 'Michel',
    'items' => ['apple', 'banana', 'cherry']
];

echo $template->render('dashboard', $data);
```

```html
<!-- views/dashboard.view -->
@extend('layouts.default')

@section('head')
    <title>{{ $title }}</title>
@endsection

@section('content')
    <h1>Bonjour {{ $user }} 👋</h1>
    <ul>
        @foreach($items as $item)
            <li>{{ $item }}</li>
        @endforeach
    </ul>
@endsection
```

## 🧩 Fonctionnalités

- Système de sections (`@section`, `@yield`, `@extend`)
- Cache des templates compilés
- Includes (`@include`)
- Conditions (`@if`, `@elseif`, `@else`, `@endif`)
- Boucles (`@foreach`, `@for`, `@while`)
- Moteur d’échappement automatique (`{{ }}`)

## 📜 Licence

Ce projet est sous licence MIT.

© 2025 — michel-ciardullo

## 💬 Contribuer

Les contributions sont les bienvenues !
