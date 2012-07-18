<?php
 /**
 * RESTful Client based on CURL
 * 
 * @author        	Almir Dzidic
 * @link			https://github.com/corysus/rest_bc
 * @version 		1.0.0
 * @date			18.07.2012.
 *
 *
 * This is basic RESTful client based on CURL. >>> To use this client you must have enabled curl on your server. <<<
 * Client support all major methods: GET, POST, PUT, DELETE and basic/digest authorization.
 * I test this client on RESTful CI Server by Phil Sturgeon, Twitter RESTful server and few others...
 * Also i added custom getCI function for use with Codeigniter URL format.
 * 
 * >> HOW TO USE <<
 * It's simple like 123 :) (also look at code in client_test.php)
 * 
 * $rest = new RESTfulCURL_Client('http://yoururl/');
 * // call like: obj->method->function.[return type]->parameters //<-- for CI server return type can be [json, php, xml, html, serialize, csv]
 * $rez = $rest->post('function.json', array('parameter1'=>'value', 'parrameter2'=>'value'));
 * print_r($rez); // print response
 *  
 */
class RESTfulCURL_Client {

	// vars
    public  $url;
    public  $authtype;
    public  $authorization;
    private $function;
    private $method;
    private $data;

	// init. 
    function __construct($url, $authtype='', $username='', $password=''){
        $this->url=$url;
        $this->authtype=$authtype; // basic or digest
        $this->authorization=$username . ":" . $password;
    }

	// post method
    function post($fun, $data=false){
        $this->method='POST';
        $this->function=$fun;
        $this->data=$data;
        return $this->curlExec();
    }

	// get method
    function get($fun, $data=false){
        $this->method='GET';
        $this->function=$fun;
        $this->data=$data;
        return $this->curlExec();
    }

	// get method for Codeigniter URL format
    function getCI($fun, $data=false){
        $this->method='GET_CI';
        $this->function=$fun;
        $this->data=$data;
        return $this->curlExec();
    }

	// put method
    function put($fun, $data=false){
        $this->method='PUT';
        $this->function=$fun;
        $this->data=$data;
        return $this->curlExec();
    }

	// delete method
    function delete($fun, $data=false){
        $this->method='DELETE';
        $this->function=$fun;
        $this->data=$data;
        return $this->curlExec();
    }

	// curl stuff
    private function curlExec(){
		
		// init. curl
        $curl = curl_init();

		// Switch methods
        switch ($this->method){
            case 'GET': // for regular use
            if ($this->data)
                $this->url = sprintf('%s?%s', $this->url.$this->function, http_build_query($this->data));
            break;

            case 'GET_CI': // use with CI url format "url/method/function/parname/paramater"
            if ($this->data)
                $ret_format=explode('.',$this->function);
                $ci_data = http_build_query($this->data,null,'/');
                $ci_data = str_replace('=','/',$ci_data);
                $this->url = $this->url.$this->function.'/'.$ci_data.'/format/'.$ret_format[1];
            break;

            case 'POST':
            curl_setopt($curl, CURLOPT_POST, 1);
            if ($this->data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $this->data);
            break;

            case 'PUT':
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
            if ($this->data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($this->data));
            break;

            case 'DELETE':
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
            if ($this->data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($this->data));
            break;
        }

		// Switch authorization type
        switch ($this->authtype){
            case 'basic':
                curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                curl_setopt($curl, CURLOPT_USERPWD, $this->authorization);
            break;

            case 'digest':
                curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
                curl_setopt($curl, CURLOPT_USERPWD, $this->authorization);
            break;
        }

        // If user using GET_CI function use CI custom URL
        if ($this->method=='GET_CI'){
            curl_setopt($curl, CURLOPT_URL, $this->url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        } else {
            curl_setopt($curl, CURLOPT_URL, $this->url.$this->function);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        }

		// return response from server
        return curl_exec($curl);
    }

} // end class -- 