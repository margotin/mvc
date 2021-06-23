<?php

declare(strict_types=1);

namespace Nitogram\Foundation;

use Dotenv\Dotenv;
use Nitogram\Foundation\Exceptions\HttpException;
use Nitogram\Foundation\Router\Router;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class App
{
    protected Router $router;

    public function __construct()
    {
        $this->initDotenv();
        if (Config::get("app.env") === "prod") {
            $this->initProductionExceptionHandler();
        }
        $this->startSession();
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

    protected function startSession(): void
    {
        Session::start();
        Session::add("_token", Session::get("_token") ?? $this->generateCsrfToken());
    }

    public function render(): void
    {
        $this->router->getInstance();
        Session::resetFlash();
    }

    public function getUrlGenerator(): UrlGeneratorInterface
    {
        return $this->router->getUrlGenerator();
    }

    protected function generateCsrfToken(): string {
        return bin2hex(random_bytes(Config::get("hashing.csrf_token_length")));
    }
}