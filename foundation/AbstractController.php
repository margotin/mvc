<?php

declare(strict_types=1);

namespace Nitogram\Foundation;

use Nitogram\Foundation\Router\Router;

abstract class AbstractController
{
    protected function redirectToRoute(string $name, array $data = []): void
    {
        header(sprintf("Location: %s", Router::getUri($name,$data)));
        die;
    }
}