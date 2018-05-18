<?php
/**
 * User: Erik Wilson
 * Date: 17-May-18
 * Time: 23:49
 */

namespace Vir\Api;


class Api
{
    private $routes;

    public function __construct()
    {
        $routes = array();
    }

    public function execute_api_call($dbh, $request)
    {
        if (array_key_exists($path, $this->routes)) {
            list($function, $methods, $auth) = $this->routes[$path];

            if ()
            $result = Database::$function($dbh, $params);
        }
    }

    public function execute_db_call($dbh, $function, $params)
    {

    }

    public function register_route($path, $params)
    {
        if ( ! array_key_exists($path, $this->routes)) {
            $this->routes[$path] = $params;
        }
    }
}
