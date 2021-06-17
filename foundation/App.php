<?php

declare(strict_types=1);

namespace Nitogram\Foundation;

use Dotenv\Dotenv;

class App
{
    public function __construct()
    {
        $this->initDotenv();
    }

    private function initDotenv():void
    {
        $dotenv = Dotenv::createImmutable(ROOT);
        $dotenv->safeLoad();
    }

    public function render(): void
    {
        echo "Hello !";
    }
}