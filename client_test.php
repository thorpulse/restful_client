<?php
require_once 'RESTfulCURL_Client.php';

// Using Twitter API's
$client = new RESTfulCURL_Client('http://api.twitter.com/'); 
$s_list = $client->get('/1/statuses/public_timeline.json', array(
    'trim_user' => true,
    'include_entities' => false,
));

echo "<pre>";
print_r(json_decode($s_list));
echo "</pre>";


// TIP: If you want to use anaother site just call $client->url and setup new url

?>