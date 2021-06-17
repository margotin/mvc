<?php

declare(strict_types=1);

namespace Nitogram\Foundation\Router;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;

class Router
{
    protected RouteCollection $routes;
    protected Request $request;
    protected RequestContext $requestContext;
    protected array $params;
    protected string $controller;
    protected string $method;

    public function __construct(array $routes)
    {
        $this->provisionRoutes($routes);
        $this->makeRequestContext();
        [$this->controller, $this->method] = $this->urlMatching();
    }

    protected function provisionRoutes(array $routes): void
    {
        $this->routes = new RouteCollection();
        foreach ($routes as $routeName => $route) {
            $this->routes->add($routeName, $route);
        }
    }

    protected function makeRequestContext(): void
    {
        $this->request = Request::createFromGlobals();
        $this->requestContext = new RequestContext();
        $this->requestContext->fromRequest($this->request);

        $method = $this->request->request->get("_method");
        if (isset($method) && in_array(strtoupper($method), Route::HTTP_METHODS)) {
            $this->requestContext->setMethod($method);
        }
        unset($method);
    }

    protected function urlMatching(): array
    {
        $matcher = new UrlMatcher($this->routes, $this->requestContext);
        $this->params = $matcher->match($this->request->getPathInfo());
        unset($matcher);

        return [$this->params["_controller"], $this->params["_method"]];
    }

    public function getInstance(): void
    {
        $this->cleanParams();
        \call_user_func_array([new $this->controller(),$this->method], $this->params);
    }

    protected function cleanParams(): void
    {
        foreach (array_keys($this->params) as $key){
            if(str_starts_with($key, '_')){
                unset($this->params[$key]);
            }
        }
    }
}