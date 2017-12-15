<?php
/**
 * Created by PhpStorm.
 * User: ulrich
 * Date: 07/12/2017
 * Time: 17:08
 */

/**
 * Class Application launch a Router Class that will be able to return the route corresponding to the user request.
 * That means that after finding the proper route, a Controller is instantiated.
 * This one will return all the variables needed to complete the view by interacting with model entities.
 * Then the Application send the requested Page to the user using a ServerResponse class.
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
        if ( null !== ($controller = $this->getController()))
        {
            try
            {
                $controller->execute();
                $this->serverResponse->setPage($controller->page());
            }
            catch (\RuntimeException $e)
            {
                $errorPage = fopen(__DIR__ .'/../../Errors/404.txt', 'a+');
                fputs($errorPage, date(DATE_RSS) . ' : ' . $e->getMessage() . PHP_EOL);
                fclose($errorPage);
            }
        }

        $this->config->parseFile(__DIR__.'/../../Config/homeLinks.xml','link');
        $cv = $this->config->getconfig('cv');
        $this->config->parseFile(__DIR__.'/../../Config/homeLinks.xml','link');
        $favicon = $this->config->getconfig('favicon');

        $this->serverResponse->page()->addVars(['cv' => $cv, 'favicon' => $favicon, 'user' => $this->user]);
        return $this->serverResponse->send();
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
            $_GET = array_merge($_GET, $matchedRoute->vars());
            return new Controller($matchedRoute->view(), $this);
        }
        catch (\RuntimeException $e)
        {
            if ($e->getCode() === Router::NO_ROUTE)
            {
                if (isset($_SESSION['error']) && $_SESSION['error'] === 'errorDB')
                {
                    $_SESSION['error'] = '';
                    $this->serverResponse->redirectErrorDB();
                }
                else
                {
                    $this->serverResponse->redirect404();
                }
            }
        }

        return null;
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
