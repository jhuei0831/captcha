<?php

    require(__DIR__.'/../vendor/autoload.php');

    use Kerwin\Captcha\Captcha;

    $captcha = new Captcha;

    header("Content-type: image/PNG");
    $captcha->getImageCode(1,5,130,30);