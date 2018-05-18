<?php
/**
 * User: Erik Wilson
 * Date: 17-May-18
 * Time: 19:10
 */

namespace Vir\Classes;


class Route
{
    private $routes;
    private $page_not_found;
    private $apis;

    public function __construct()
    {
        $this->routes         = array();
        $this->apis           = array();
        $this->page_not_found = null;
    }


    public function register_route($path, $file)
    {
        if ( ! array_key_exists($path, $this->routes)) {
            $this->routes[$path] = $file;
        }
    }

    public function register_api($path, $file)
    {
        if ( ! array_key_exists($path, $this->apis) && ! array_key_exists($path, $this->routes)) {
            $this->apis[$path] = $file;
        }
    }

    public function get_routes()
    {
        return $this->routes;
    }

    public function load_route($path)
    {
        if (array_key_exists($path, $this->routes) && $_SERVER['REQUEST_METHOD'] == \Vir\Http\Http::GET) {
            require_once $this->routes[$path];
        } elseif ( ! $this->load_api($path)) {
            require_once $this->page_not_found;

            return false;
        }

        return true;
    }

    public function load_api($path)
    {
        $paths = explode('/', $path);
        foreach ($paths as $key=>$val) {
            $new_path = implode('/', array_slice($paths, 0, $key + 1));
            if (array_key_exists($new_path, $this->apis)) {
                require_once $this->apis[$new_path];
                return true;
            }
        }

        return false;
    }

    public function set_page_not_found($file)
    {
        $this->page_not_found = $file;
    }
}
