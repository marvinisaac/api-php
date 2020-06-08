<?php

    require '../vendor/autoload.php';

    $api = (new Api\Api())->get();
    $api->run();
