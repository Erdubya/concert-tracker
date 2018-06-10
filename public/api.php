<?php
/**
 * User: Erik Wilson
 * Date: 17-May-18
 * Time: 18:51
 */

$api = new \Vir\Api\Api();
\Vir\Api\Endpoints::register_endpoints($api, '/api/v1');

$request = new \Vir\Http\Request();

$response = $api->execute_api_call($dbh, $request);
