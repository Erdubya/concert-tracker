<?php
/**
 * User: Erik Wilson
 * Date: 17-May-18
 * Time: 18:51
 */

//var_dump($_SERVER);

$api = new \Vir\Api\Api();
\Vir\Api\Endpoints::register_endpoints($api);


if (isset($_REQUEST['json'])) {
    $decoded = json_decode(stripslashes($_REQUEST['data']), true);
    if (is_null($decoded)) {
        $response['status']  = array(
            'type'  => 'error',
            'value' => 'Invalid JSON value found',
        );
        $response['request'] = $_REQUEST['json'];
    } else {
        $response['status'] = array(
            'type'  => 'message',
            'value' => 'Valid JSON value found',
        );
        //Send the original message back.
        $response['request'] = $decoded;
    }
} else {
    $response['status'] = array(
        'type'  => 'error',
        'value' => 'No JSON value set',
    );
}
$encoded = json_encode($response);
header('Content-type: application/json');
exit($encoded);
