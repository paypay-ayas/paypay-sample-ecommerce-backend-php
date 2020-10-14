<?php
require_once 'utils.php';
require_once './vendor/autoload.php';

$appConfig = [
    'hostname' => "localhost:5000"
];
$routes = [
    '/cakes',
    '/create-qr',
    '/order-status'
];

use PayPay\OpenPaymentAPI\Client;

function site_url($echome = false)
{
    global $appConfig;
    $host = $appConfig['hostname'];
    $host = htmlspecialchars($host);
    if ($echome) {
        print_r( $host . '/');
    }
    return ('http://' . $host . '/');
}

function url_path($getArr = false)
{
    $k = htmlspecialchars(filter_input(INPUT_SERVER,'REQUEST_URI'));
    $k_arr = explode('/', $k);
    return $getArr ? $k_arr : $k;
}

function load_route($client)
{
    global $routes;
    $urlparts = url_path(true);
    $len = count($urlparts);
    $routeIndex = array_search('/' . ($len>0?$urlparts[1]:''), $routes);
    if ($routeIndex !== FALSE) {
        $routePath = $routes[$routeIndex];
        $intended_file = "./src/routes" . $routePath . ".php";
        $urlparam = '';
        if (file_exists($intended_file)) {
            if (isset($urlparts[2] )&&$urlparts[2] && $urlparts[2] != '') {
                $urlparam = $urlparts[2];
            }
            require_once($intended_file);
        }
    }else{
        print_r('No route found!');
    }
}


$client = new Client(
    [
        'API_KEY' => getenv('API_KEY'),
        'API_SECRET' => getenv('API_SECRET'),
        'MERCHANT_ID' => getenv('MERCHID')
    ],
    false //set to true for production mode
);
load_route($client);
