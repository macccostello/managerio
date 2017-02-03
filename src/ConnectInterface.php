<?php 

namespace Managerio;

interface ConnectInterface{

	public function client();

	public function get($uri,$requestResult);
	
	public function post($uri,$data);
	
	public function put($uri,$data);

	public function requestResult($response,$requestResult);

	public function keyLink($name,$uri);
}