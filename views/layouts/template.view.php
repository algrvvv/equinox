<?php

use Imissher\Equinox\app\core\Application;

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>

    <nav class="py-2 bg-light border-bottom">
        <div class="container d-flex flex-wrap">
            <ul class="nav me-auto">
                <li class="nav-item"><a href="/" class="nav-link link-dark px-2 active" aria-current="page">Главная</a></li>
            </ul>
            <ul class="nav">
                <?php if (Application::isGuest()) : ?>
                    <li class="nav-item"><a href="/login" class="nav-link link-dark px-2">Вход</a></li>
                    <li class="nav-item"><a href="/register" class="nav-link link-dark px-2">Регистрация</a></li>
                <?php else : ?>
                    <li class="nav-item"><a href="/profile" class="nav-link link-dark px-2">Профиль</a></li>
                    <li class="nav-item">
                        <form action="/logout" method="post"><button class="nav-link link-dark px-2" type="submit">Выход</button></form>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <div style="width: 75%; margin: 10px auto;">
        <?php if (Application::$app->session->getFlash('success')) : ?>
            <div class="alert alert-success">
                <?php echo Application::$app->session->getFlash('success') ?>
            </div>
        <?php endif; ?>

        <?php if (Application::$app->session->getFlash('error')) : ?>
            <div class="alert alert-danger">
                <?php echo Application::$app->session->getFlash('error') ?>
            </div>
        <?php endif; ?>
    </div>

    {{ content }}

</body>

</html>
