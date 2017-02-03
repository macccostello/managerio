<?php 

namespace Managerio;

interface Connector{

	public $username;
	public $password;
	public $home;
	public $base_uri;
	public $business;
	public $businessID;

	public function client();

	public function get($uri,$requestResult);
	
	public function post($uri,$data);
	
	public function put($uri,$data);

	public function requestResult($response,$requestResult);

	public function keyLink($name,$uri);
}