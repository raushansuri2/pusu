<?php
//echo "<pre>"; print_r($_SERVER); die;
//echo "<pre>"; print_r($_SERVER); die;
//require 'vendor/autoload.php';
//require 'google-auth-library-php-main/autoload.php';
require '/home/demo5ev/public_html/datingapp/webroot/guzzle/vendor/autoload.php';;

use Google\Auth\Credentials\ServiceAccountCredentials;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class FcmHttpV1 {
    private $credentials;
    private $client;
    private $projectId;

    public function __construct($serviceAccountPath) {
        $this->credentials = new ServiceAccountCredentials(
            'https://www.googleapis.com/auth/cloud-platform',
            $serviceAccountPath
        );
        $this->client = new Client(['base_uri' => 'https://fcm.googleapis.com']);
        $this->projectId = json_decode(file_get_contents($serviceAccountPath), true)['project_id'];
    }

    public function sendMessage($fields) {
        $url = sprintf('/v1/projects/%s/messages:send', $this->projectId);
        
        // $message = [
        //     'message' => [
        //         'token' => $token,
        //         'notification' => [
        //             'title' => $title,
        //             'body' => $body,
        //         ],
        //         'data' =>  $new_data
        //         // 'data' => [
        //         //     //'data' => $new_data,  // Add any custom data here
        //         //     'name'=>'raushan',
        //         //     //'compnay'=>'ABC'
        //         // ]
                
        //     ]
        // ];

        try {
            $response = $this->client->post($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->credentials->fetchAuthToken()['access_token'],
                    'Content-Type' => 'application/json',
                ],
                'json' => $fields,
            ]);

            return $response->getStatusCode() . ': ' . $response->getBody();
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                return $e->getResponse()->getStatusCode() . ': ' . $e->getResponse()->getBody();
            }
            return $e->getMessage();
        }
    }
}


//echo "<pre>";
//print_r($_REQUEST); die;
// Usage
$serviceAccountPath = 'jsonfile.json';
$fcm = new FcmHttpV1($serviceAccountPath);

// $token = "dPj5AAQJSbWwHGAAaH7daZ:APA91bE32qYnIT1IwmTKoQnz4_vijzq8Rcxp8btuC3Zci3ZzpIP23AeTkZBi4C6Xohf_-RA4936bUrTJOPVujY-roMLNTVbWA96sgTE8orCN1O1nrm3A8aOAP_BEl6b71dYk7XOBo6R7";
// $title = 'SHOW THE NOTIFICATION';
// $body = 'Hi LYV.';
$token = $_REQUEST['deviceToken'];
$BODY = (array)json_decode($_REQUEST['body']);
//print_r($BODY); die;
$title = $BODY['message'];
$body  = $_REQUEST['body'];
$new_data = ["name"=>"raushan", "age"=>"30"];
$fields = array(
    'message'=>array(
        'token'  => $_REQUEST['deviceToken'],
        //'topic'=>'Satish',
        // 'notification'=>[
        //     'title' => $BODY['message'],
        //     'body'=>  $BODY['message'],
        // ],
        'data'=>['data'=>$_REQUEST['body']],
        "android"=>["priority"=>"high"],
        'apns'=>['payload'=>[
                    'aps'=>[
                        'alert'=>['title'=>$BODY['message'],'body'=>$BODY['message']], 
                        "content-available"=>1, 
                        "sound"=>"default"
                    ]
                ]
            ]
    )
);
//$result = $fcm->sendMessage($token, $title, $body, $BODY);
$result = $fcm->sendMessage($fields);

//return $result;

//echo "<pre>";
print_r($result);
die;