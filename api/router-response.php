<?php 

class ResponseRouter
{
	
	public static function sendResponse($client_message=null, $data=null)
	{
		global $client_messages;
		$response_message = ( is_int($client_message) ) ? $client_messages[$client_message][0] : $client_message;
		$http_code = $client_messages[$client_message][1];

	    $response["status"] = $http_code;
	    $response["message"] = ( is_null($response_message) ) ? "" : $response_message;
	    if ( !is_null($data) ) $response["data"] = $data;

	    $json_response = json_encode($response);

	    http_response_code($http_code);

	    error_log("---------------------------------------- API ENDS ----------------------------------------");
	    error_log("");
	    echo $json_response;
	}

}
