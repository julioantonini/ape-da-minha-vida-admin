<?php
//error_reporting(0);
// Bootup the Composer autoloader
include __DIR__ . '/vendor/autoload.php';  

use Mautic\Auth\ApiAuth;
use Mautic\MauticApi;

session_start();

// ApiAuth->newAuth() will accept an array of Auth settings
$settings = array(
    'userName'   => 'zooagencia',             // Create a new user       
    'password'   => 'wap61121432'              // Make it a secure password
);

// Initiate the auth object specifying to use BasicAuth
$initAuth = new ApiAuth();
$auth = $initAuth->newAuth($settings, 'BasicAuth');

// Nothing else to do ... It's ready to use.
// Just pass the auth object to the API context you are creating.

$apiUrl = 'https://marketing.ismokay.com';
$api = new MauticApi();

$contactApi = $api->newApi('contacts', $auth, $apiUrl);

/* 
$response = $segmentApi->removeContact(8, 456);
if (!isset($response['success'])) {
    echo 'erro';
}else{
    echo 'sucesso';
} */

$where = 'email:web@zoo.digital';
$contacts = $contactApi->getList($where);

foreach($contacts as $item):
    foreach($item as $test):
        print_r($test['id']);
    endforeach;
endforeach;