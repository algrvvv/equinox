<?php

/**
 * @var Exception $exception
 */

?>

<div class="d-flex align-items-center justify-content-center vh-100">
    <div class="text-center">
        <h1 class="display-1 fw-bold"><?= $exception->getCode() ?></h1>
        <p class="fs-3"> <span class="text-danger">Opps!</span> <?= $exception->getMessage() ?></p>
        <a href="/" class="btn btn-primary">Go Home</a>
    </div>
</div>
