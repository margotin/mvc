<?php

declare(strict_types=1);

use Nitogram\Foundation\App;

define("ROOT", \dirname(__DIR__));

require_once ROOT . "/vendor/autoload.php";

$app = new App();
$app->render();
