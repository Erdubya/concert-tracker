<?php
/**
 * User: Erik Wilson
 * Date: 18-May-18
 * Time: 12:25
 */

namespace Vir\Http;

class Request extends \GuzzleHttp\Psr7\Request
{
    public function __construct()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];
        $headers = getallheaders();
        $body = null;
        $version = '1.1';
        parent::__construct($method, $uri, $headers, $body, $version);
    }
}
