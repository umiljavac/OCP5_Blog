<?php
/**
 * Created by PhpStorm.
 * User: ulrich
 * Date: 07/12/2017
 * Time: 17:21
 */

/**
 * Router class stock all the routes available and return with the getRoute() method the one which match with the user request.
 */

namespace Main;


class Router
{
    protected $routes = [];
    const NO_ROUTE = 1;

    public function addRoute(Route $route)
    {
        if (!in_array($route, $this->routes))
        {
            $this->routes[] = $route;

        }
    }

    public function getRoute($url)
    {
        foreach ($this->routes as $route)
        {
            if (($varValues = $route->matchUrl($url)) !== false)
            {
                if ($route->hasVars())
                {
                    $varsNames = $route->varsNames();
                    $listVars = [];

                    foreach ($varValues as $key => $match)
                    {
                        if ($key !== 0)
                        {
                            $listVars[$varsNames[$key - 1]] = $match;
                        }
                    }

                    $route->setVars($listVars);
                }

                return $route;
            }

        }

        throw new \RuntimeException('Aucune route ne correspond à l\'URL', self::NO_ROUTE);
    }
}