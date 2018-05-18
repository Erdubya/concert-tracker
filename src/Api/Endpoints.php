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
     * @param Api $api Api to register endpoints for
     */
    public static function register_endpoints(\Vir\Api\Api $api)
    {
        $api->register_route('/concert/create', [
            'add_new_concert',
            [Http::POST],
            false
        ]);
    }
}
