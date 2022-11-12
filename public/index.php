<?php

$maintenance = __DIR__.'/../storage/framework/maintenance.php';
if(file_exists ($maintenance)){
    require $maintenance;
}

require __DIR__.'/../vendor/autoload.php';

require __DIR__.'/../bootstrap/app.php';
