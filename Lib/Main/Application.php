<?php
/**
 * Created by PhpStorm.
 * User: ulrich
 * Date: 07/12/2017
 * Time: 17:08
 */

/**
 * Class Application
 */

namespace Main;


class Application
{
    protected $userRequest;
    protected $serverResponse;
    protected $user;
    protected $config;

    public function __construct()
    {
        $this->userRequest = new UserRequest();
        $this->serverResponse = new ServerResponse();
        $this->user = new User();
        $this->config = new Config();
    }

    public function run()
    {
       $controller = $this->getController();
       $controller->execute();

       $this->serverResponse->setPage($controller->page());
       $this->serverResponse->send();
    }

    public function getController()
    {
        $router = new Router();

        $xml = new \DOMDocument();
        $xml->load(__DIR__.'/../../Config/routes.xml');
        $xmlRoutes = $xml->getElementsByTagName('route');

        foreach ($xmlRoutes as $route)
        {
            $vars = [];

            if ($route->hasAttribute('vars'))
            {
                $vars =  explode(',', $route->getAttribute('vars'));
            }

            $router->addRoute(new Route($route->getAttribute('url'), $route->getAttribute('view'), $vars));
        }

        try
        {
            $matchedRoute = $router->getRoute($this->userRequest->requestUri());
        }
        catch (\RuntimeException $e)
        {
            if ($e->getCode() == Router::NO_ROUTE)
            {
                if ($_SESSION['error'] === 'errorDB')
                {
                    $_SESSION['error'] = '';
                    $this->serverResponse->redirectErrorDB();
                }
               $this->serverResponse->redirect404();
            }
        }

        $_GET = array_merge($_GET, $matchedRoute->vars());
        return new Controller($matchedRoute->view(), $this);

    }

    public function userRequest()
    {
        return $this->userRequest;
    }

    public function serverResponse()
    {
        return $this->serverResponse;
    }

    public function config()
    {
        return $this->config;
    }

    public function user()
    {
        return $this->user;
    }
}
