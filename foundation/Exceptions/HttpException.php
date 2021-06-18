<?php

declare(strict_types=1);

namespace Nitogram\Foundation\Exceptions;

use Exception;
use JetBrains\PhpStorm\NoReturn;

class HttpException extends Exception
{
    #[NoReturn]
    public static function sendResponse(int $httpCode = 404, string $message = "Page not found !"): void
    {
        http_response_code($httpCode);
        echo "<h1>Error $httpCode : $message</h1>";
        die;
    }
}