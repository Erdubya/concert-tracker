<?php
/**
 * User: Erik Wilson
 * Date: 12-May-18
 * Time: 18:44
 */

// initial autoload
require_once dirname(__FILE__) . "/../vendor/autoload.php";

if (!$config = config_loader()) {
    request_install();
}

// start the session and connect to DB
session_start();
$dbh = \Vir\Classes\Database::create_pdo($config->database);

cookie_loader($dbh);

// create route object
$routes = new \Vir\Classes\Route();

// add main page routes
$routes->register_route('/', 'home.php');
$routes->register_route('/install', 'install.php');
$routes->register_route('/login', 'login.php');
$routes->register_route('/logout', 'logout.php');
$routes->register_route('/register', 'register.php');
$routes->register_route('/concerts', 'concerts.php');
$routes->register_route('/artists', 'artists.php');
$routes->register_route('/data/import', 'import.php');
$routes->register_route('/data/export', 'export.php');
$routes->register_route('/profile/edit', 'profile.php');

// add an api
$routes->register_api('/api/v1', 'api.php');

// add the 404
$routes->set_page_not_found('404.php');

list($path) = explode('?', $_SERVER['REQUEST_URI']);
$routes->load_route($path);
