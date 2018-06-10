<?php
/**
 * User: Erik Wilson
 * Date: 18-May-18
 * Time: 00:39
 */

namespace Vir\Api;


use Vir\Http\Http;

class Endpoints
{
    /**
     * @param \Vir\Api\Api $api Api to register endpoints for
     * @param string $prefix URL path prefix for the api
     */
    public static function register_endpoints(\Vir\Api\Api $api, string $prefix)
    {
        $api->register_route(
            $prefix . '/concert/create',
            ['add_new_concert', [Http::POST], true]
        );

        $api->register_route(
            $prefix . '/concert/get/next',
            ['get_next_concert', [Http::GET], true]
        );

        $api->register_route(
            $prefix . '/user/login',
            ['get_user_login_info', [HTTP::POST], false]
        );
    }
}
