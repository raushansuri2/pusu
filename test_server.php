<?php
// Test server response
$url = 'http://localhost:8769/admin';
$context = stream_context_create([
    'http' => [
        'method' => 'GET',
        'header' => 'Content-Type: application/x-www-form-urlencoded'
    ]
]);

$response = file_get_contents($url, false, $context);
echo "Response from server:\n";
echo $response . "\n";
echo "HTTP response headers: " . $http_response_header[0] . "\n";
?>
