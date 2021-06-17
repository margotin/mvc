<?php

declare(strict_types=1);

namespace Nitogram\Foundation\Router;

use Nitogram\Foundation\AbstractController;
use Symfony\Component\Routing\Route as SymfonyRoute;

/**
 * @method static get(string $string, string[] $array)
 * @method static head(string $string, string[] $array)
 * @method static post(string $string, string[] $array)
 * @method static put(string $string, string[] $array)
 * @method static patch(string $string, string[] $array)
 * @method static delete(string $string, string[] $array)
 */
class Route
{
    public const HTTP_METHODS = [
        'GET',
        'HEAD',
        'POST',
        "PUT",
        "PATCH",
        "DELETE"
    ];

    public static function __callStatic(string $httpMethod, array $arguments): SymfonyRoute
    {
        if (!\in_array(strtoupper($httpMethod), static::HTTP_METHODS)) {
            throw new \BadMethodCallException(
                sprintf("HTTP method \"%s\" is not supported !", $httpMethod)
            );
        }

        [$uri, $action] = $arguments;

        return static::makeRoute($uri, $action, $httpMethod);
    }

    protected static function makeRoute(string $uri, array $action, string $httpMethod): SymfonyRoute
    {
        [$controller, $method] = $action;

        try{
            static::checkAction($controller, $method);
        }catch (\InvalidArgumentException $e){
            throw new \InvalidArgumentException($e->getMessage());
        }

        return new SymfonyRoute($uri, [
            "_controller" => $controller,
            "_method" => $method
        ],
            options: ["utf8" => true],
            methods: [$httpMethod]
        );
    }

    protected static function checkAction(string $controllerName, string $methodName): void
    {
        if(!class_exists($controllerName)){
            throw new \InvalidArgumentException(
                sprintf("Controller \"%s\" doesn't exist !", $controllerName)
            );
        }

        if(!method_exists($controllerName, $methodName)) {
            throw new \InvalidArgumentException(
                sprintf("Method \"%s\" of controller \"%s\" doesn't exist !", $methodName, $controllerName)
            );
        }

        if(!\is_subclass_of($controllerName, AbstractController::class)){
            throw new \InvalidArgumentException(
                sprintf(
                    "Controller \"%s\" must be an instance of %s !",
                    $controllerName,
                    AbstractController::class
                )
            );
        }
    }
}