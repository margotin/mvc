<?php

declare(strict_types=1);

namespace Nitogram\Foundation;

use Dotenv\Dotenv;
use Nitogram\Foundation\Router\Router;

class App
{
    protected Router $router;

    public function __construct()
    {
        $this->initDotenv();
        $this->router = new Router(require ROOT . "/app/routes.php");
    }

    private function initDotenv(): void
    {
        $dotenv = Dotenv::createImmutable(ROOT);
        $dotenv->safeLoad();
    }

    public function render(): void
    {
        $this->router->getInstance();
    }
}