<?php 

namespace Managerio;

use GuzzleHttp\Client as GuzzleClient;
	
class Connect {
	public  $username = "administrator"; 
	public  $password = "mcdo1234";
	public  $home     = "http://ec2-54-202-145-45.us-west-2.compute.amazonaws.com/api"; 
	public  $base_uri = "http://ec2-54-202-145-45.us-west-2.compute.amazonaws.com/api/"; 
	public  $business = "CMA Ortigas Branch";
	public  $businessID = "6bcc2740-8920-4055-a7ae-951fac5ab194";  
	public function __construct($auth){
		if($auth != "default"){
			$this->username   =  $auth['username'];
			$this->password   =  $auth['password'];
			$this->home       =  $auth['home']; 
			$this->base_uri   =  $auth['base_uri']; 
			$this->business   =  $auth['business'];
			$this->businessID =  $auth['businessID']; 
		} 
	}
	// Initialize base uri and auth
	public function client(){
		$client =  new GuzzleClient(['base_uri' => $this->base_uri, 'auth' => [$this->username,$this->password]]);
		return $client;
	}


	// set $uri = uri you want to retrieve , $requestResult = Result you want to retrieve 
	public function get($uri="", $requestResult="array"){
		$uri = $uri == "" ? $this->home : $uri ; 
		$client   = $this->client();
		$response = $client->get($uri);
		$result   = $this->requestResult($response,$requestResult);
		return $result;
	} 


	//Post
	public function post($uri,$data){
		$client = $this->client();
		$request = $client->request('POST', $uri, $data);	
		return $request;
	}

	//Put
	public function put($uri,$data){
		$client = $this->client();
		$response = $client->request('PUT',$uri, $data);
		return $response->getStatusCode();
	}

	// result from response 
	public function requestResult($response,$requestResult="default"){
		$result = "No Result"; 
		switch($requestResult){
			case "StatusCode":     
				$result =  $response->getStatusCode(); 
				break; 
			case "Reason":
				$result = $response->getReasonPhrase();
				break;

			case "HeaderExist":
				if ($response->hasHeader('Content-Length')) { $result =  "It exists"; }
				break;

			case "HeaderResponse":
				if ($response->hasHeader('Content-Length')) {  
					$string = "";
					foreach ($response->getHeaders() as $name => $values) {
	    				$string .=  $name . ': ' . implode(', ', $values) . "\r\n";
					}
					$result = $string;
				} else {
					$result = "No Header Response"; 
				}
				break;

			case "Body":
				$result = $response->getBody();
				break;	
			case "BodyString":
				$body = $response->getBody();
				$result = (string) $body;
				break;		
			default:
				$header =  $response->hasHeader('Content-Length') ?  $response->getHeaders() : "No Header";
				$result = array("StatusCode" => $response->getStatusCode(),"Reason" => $response->getReasonPhrase(),"Body" => $response->getBody(), "Header" => $header );	
		}
		return $result;
	}

	// Key Links 
	public function keyLink($name,$uri=""){
		$links = array(
			"Home"      => "",
			"Customers" =>  "/ec37c11e-2b67-49c6-8a58-6eccb7dd75ee", // Customer API
			"Invoices"   => "/ad12b60b-23bf-4421-94df-8be79cef533e" // invoice API
		);
		return $this->base_uri.$this->businessID.$links[$name].$uri; 
	}

	/*********************The Functions *****************************/

	// Get business key and Name
	public function business(){
		$business = $this->get("index.json");
		return json_decode($business['Body']);
	}

	//Get All Customer Keys
	public function customerKeys(){
		$customer = $this->get($this->keyLink("Customers","/index.json"));
		return json_decode($customer['Body']);	
	}
	
	// Add Customer
	public function addCustomer($array){
		$data['json'] =  $array;
		$response = $this->post($this->keyLink("Customers"),$data);
		$header   = $response->getHeaders();  
		return $header['Location']; // new loacation id of added customer
	}

	// Get Customer
	public function getCustomer($customerkey){
		$customer = $this->get($this->keyLink("Home","/$customerkey.json"));
		return  json_decode($customer['Body']);	
	}

	// Update Customer 
	public function updateCustomer($customerkey){
		$data['json'] =  array(
				"Name" => "Robin Hood",
		  		"BillingAddress" => "Test",
		  		"Email" =>  "Freeda@gmail.com",
		  		"BusinessIdentifier" =>  "999 555 555",
		  		"StartingBalanceType" => "Credit"
			);
		$status = $this->put($this->keyLink("Home","/$customerkey.json"),$data);
		return $status; //  must be 200
	}
}