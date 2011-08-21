<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Router
{
	public $controller = '';
	public $method = '';
	public $args = array();
	public $segments = array();

	public function __construct()
	{
		global $_cfg;
		
		if ( ! isset($_SERVER['PATH_INFO']) ) $_SERVER['PATH_INFO'] = '';
		
		$this->segments = $this->args 	= preg_split('/\//', trim($_SERVER['PATH_INFO'], '/'), -1, PREG_SPLIT_NO_EMPTY);
		$this->controller 				= ucfirst(strtolower(trim((count($this->args) > 0)?array_shift($this->args):$_cfg['rout']['index'], '_')));
		$this->method					= strtolower(trim((count($this->args) > 0)?array_shift($this->args):'index', '_'));

	}
}

/* End of file router.php */
/* Location: ./app/router.php */