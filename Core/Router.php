<?php

namespace App;

use AltoRouter;
use App\Auth\AuthenticationException;
use App\Auth\Authenticator;
use Exception;

class Router
{
    /**
     * @var string
     */
    private $viewPath;
    /**
     * @var AltoRouter
     */
    private $router;

    public function __construct($viewPath)
    {
        $this->viewPath = $viewPath;
        $this->router = new AltoRouter();
    }
    public function match(string $url, string $view, ?string $title = null): self
    {
        $this->router->map('POST|GET', $url, $view, $title);
        return $this;
    }
    public function get(string $url, string $view, ?string $title = null): self
    {
        $this->router->map('GET', $url, $view, $title);
        return $this;
    }
    public function post(string $url, string $view, ?string $title = null): self
    {
        $this->router->map('POST', $url, $view, $title);
        return $this;
    }
    public function run()
    {
        $match = $this->router->match();
        if (!$match) {
            throw new Exception('Page Not Found');
        }
        $router = $this;
        $params = $match['params'] ?? [];

        $isAdmin = strpos($match['target'], 'admin/');
        $layout = $isAdmin === false ? 'partials/default.php' : 'admin/partials/default.php';

        if ($isAdmin !== false) {
            try {
                Authenticator::checkPermission();
            } catch (AuthenticationException $e) {
                header('location: ' . $router->url('login') . '?forbidden');
                return;
            }
        }
        ob_start();
        require $this->viewPath .  $match['target'] . '.php';
        $content = ob_get_clean();
        require $this->viewPath . $layout;
    }
    public function url(string $name, array $params = []): string
    {
        return $this->router->generate($name, $params);
    }
}
