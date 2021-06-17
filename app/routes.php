<?php

declare(strict_types=1);

use App\Controllers\BaseController;
use Nitogram\Foundation\Router\Route;

return [
    'index' => Route::get('/', [BaseController::class, 'index']),
];

