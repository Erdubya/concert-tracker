<?php
/**
 * User: Erik Wilson
 * Date: 17-May-18
 * Time: 23:49
 */

namespace Vir\Api;

use Vir\Http\Http;
use Vir\Http\Request;

class Api
{
    private $routes;

    public function __construct()
    {
        $this->routes = array();
    }

    public function execute_api_call($dbh, Request $request)
    {
        if (array_key_exists($request->getUri()->getPath(), $this->routes)) {
            list($function, $methods, $auth) = $this->routes[$request->getUri()->getPath()];
            if (in_array($request->getMethod(), $methods)) {
                if ($request->getMethod() != Http::GET) {
                    parse_str(file_get_contents("php://input"),$params);
                } else {
                    $params = $_GET;
                }

//                var_dump($params);

                $result = Database::$function($dbh, $params);

                $this->send_json_response(201, json_encode($result), ['Content-Type' => 'application/json']);
            }
        }


        return null;
    }

    public function send_json_response(int $status = 200, string $body = null, array $headers = [])
    {
        header('HTTP/1.1 ' . $status . ' ' . Http::get_status_phrase($status));

        foreach ($headers as $header => $value) {
            header($header . ': ' . $value);
        }

        echo $body;
    }

    public
    function register_route(
        $path,
        $params
    ) {
        if ( ! array_key_exists($path, $this->routes)) {
            $this->routes[$path] = $params;
        }
    }

    public function get_routes()
    {
        return $this->routes;
    }
}
