**Basic RESTful CURL based client**
====================================

Hi, this is just another PHP RESTful CURL based client.
This client:
 - support all major methods: GET,POST,PUT,DELETE.
 - basic and digest authorization
 - custom getCI function to use with Codeigniter URL format
 - tested with RESTful PHP Server by Phil Sturgeon, Twitter RESTful server and few others...
 
**HOW TO USE**
It's simple like 123 :)

<?php
$rest = new RESTfulCURL_Client('http://yoururl/');
$rez = $rest->post('function.json', 
		array('parameter1'=>'value', 
					'parrameter2'=>'value'
		));
print_r($rez); // print response
?>