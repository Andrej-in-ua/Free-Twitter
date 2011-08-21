<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users_Hook
{
	public static function checkLogin()
	{
		$LZ = & get_instance();
		if ( ! isset($_SESSION['login'])
			|| $_SESSION['login'] !== TRUE 
			|| $_SESSION['ip'] != $LZ->ip
			|| $_SESSION['user_agent'] != $_SERVER['HTTP_USER_AGENT'] )
		{	
			$_SESSION['login'] = FALSE;
			unset($_SESSION['user_id']);
			unset($_SESSION['ip']);
			unset($_SESSION['user_agent']);
		}
	}
}

/* End of file users.php */
/* Location: ./app/hooks/users.php */