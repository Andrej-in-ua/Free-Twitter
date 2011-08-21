<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function __autoload($class_name) { 
	if ( file_exists(BASEPATH.'models/'.$class_name.'.php') ){
		
		include_once BASEPATH.'models/'.$class_name.'.php';
		
	} else if ( file_exists(BASEPATH.'hooks/'.$class_name.'.php') ) {
		
		include_once BASEPATH.'hooks/'.$class_name.'.php';
		
	}
} 

function e404()
{
	global $app;
	
	if ( ! is_object($app) ){
		LZ_Controller::view("404");
	} else {
		$app->view("404");
		unset($app);
	}
	
	header('HTTP/1.0 404 Not Faund');
	exit;
}


// ------------------------------------------------------------------------

/**
 * Header Redirect.
 *
 * @access	public
 * @param	string	the URL
 * @param	string	the method: location or redirect
 * @return	string
 */
if ( ! function_exists('redirect'))
{
	function redirect($uri = '', $method = 'location', $http_response_code = 302)
	{
		switch($method)
		{
			case 'refresh'	: header("Refresh:0;url=".$uri);
				break;
			default			: header("Location: ".$uri, TRUE, $http_response_code);
				break;
		}
		exit;
	}
}

/* End of file functions.php */
/* Location: ./app/functions.php */