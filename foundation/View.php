<?php

declare(strict_types=1);

namespace Nitogram\Foundation;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class View
{
    public static function render(string $view, array $data = []): void
    {
        $view = str_replace(".", "/", $view);
        if (!static::viewExist($view)) {
            throw new \InvalidArgumentException(
                sprintf("View \"%s\" doesn't exist !", $view)
            );
        }

        $twig = static::initTwig();

        echo $twig->render(
            sprintf("%s.%s", $view, Config::get("twig.template_extension")),
            $data
        );
    }

    protected static function viewExist(string $view): bool
    {
        return file_exists(
            sprintf("%s/resources/views/%s.%s", ROOT, $view, Config::get("twig.template_extension"))
        );
    }
    protected static function initTwig(): Environment
    {
        $loader = new FilesystemLoader(Config::get("twig.views_directory"));
        return new Environment($loader, [
            "cache" => Config::get("twig.cache_directory"),
            "auto_reload" => Config::get("twig.auto_reload")
        ]);

    }

}