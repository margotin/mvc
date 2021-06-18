<?php

declare(strict_types=1);

namespace Nitogram\Foundation;

use Dotenv\Dotenv;
use Nitogram\Foundation\Exceptions\HttpException;
use Nitogram\Foundation\Router\Router;

class App
{
    protected Router $router;

    public function __construct()
    {
        $this->initDotenv();
        if (env("APP_ENV", "prod") === "prod") {
            $this->initProductionExceptionHandler();
        }
        $this->router = new Router(require ROOT . "/app/routes.php");
    }

    private function initDotenv(): void
    {
        $dotenv = Dotenv::createImmutable(ROOT);
        $dotenv->safeLoad();
    }

    protected function initProductionExceptionHandler(): void
    {
        set_exception_handler(
            fn() => HttpException::sendResponse(500, "Houston, we have a problem!")
        );
    }

    public function render(): void
    {
        $this->router->getInstance();
    }
}