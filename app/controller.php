<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
	В данной версии не искользует паттерн MVC
	т.к. изначально небыло нужды в отделении модели от контролера,
	поэту с базой данных работает непосредственно контролер
*/

// У каждого базового приложения может быть свой управляющий контроллер
// Библиотеки не должны знать о там кто именно их вызывает
if ( ! function_exists('get_instance') ) {
	function &get_instance() { return LZ_Controller::get_instance(); }
}

class LZ_Controller
{
	
	private static $instance;
	
	public $DB = NULL;
	public $cfg;
	public $meta = array('title' => '', 'meta' => array());
	public $return_page = true;
	public $ip;

	
	protected function __construct()
	{
		// После инициализации нам не нужна лишняя глобальная переменная
		global $_cfg;
		$this->cfg =& $_cfg;
		unset($cfg);
		
		// Get user ip
		if ( ! empty($_SERVER['HTTP_CLIENT_IP']) ){ $this->ip=$_SERVER['HTTP_CLIENT_IP'];
		} elseif ( ! empty($_SERVER['HTTP_X_FORWARDED_FOR']) ){$this->ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
		} else { $this->ip=$_SERVER['REMOTE_ADDR']; }

		// Создаем ссылку на себя
		self::$instance =& $this;

		// Construct db
		try {
			$this->DB = @new PDO($this->cfg['db']['dsn'], $this->cfg['db']['username'], $this->cfg['db']['password'], $this->cfg['db']['atr']);
		} catch (PDOException $e) {
			die($e->getMessage());
		}
		$this->DB->exec("SET NAMES `utf8` COLLATE 'utf8_general_ci';");
		
		// Проверяем авторизацию
		// Я знаю что так делать плохо, но полноценные хуки я еще не реализовал
		Users_Hook::checkLogin();
	}
	
	public static function &get_instance() { return self::$instance; }
		
	// Представления
	public static function view($view, $args = array())
	{
		if ( ! file_exists(BASEPATH.'views/'.$view.'.php') ) return false;
		extract($args);
		// В случае если в php отключены короткие теги их стоит заменить
		echo eval('?>'.preg_replace("/;*\s*\?>/", "; ?>", str_replace('<?=', '<?php echo ', file_get_contents(BASEPATH.'views/'.$view.'.php'))));
	}
	
	// Модель
	protected function model($model)
	{
		if ( ! file_exists(BASEPATH.'models/'.$model.'.php') ) return false;
		include_once(BASEPATH.'models/'.$model.'.php');
		$model .= 'Model';
		return new $model($this->DB);
	}
	
	// Бибоиотеки
	protected function load($lib)
	{
		if ( ! file_exists(BASEPATH.'libraries/'.$lib.'.php') ) return false;
		include_once(BASEPATH.'libraries/'.$lib.'.php');
		$this->$lib = new $lib();
	}
	
	public function __destruct()
	{
		if ( $this->return_page ) {
			$this->meta['content'] = ob_get_contents();
			ob_end_clean();
			$this->view('page', $this->meta);
		}
	}
}

/* End of file controller.php */
/* Location: ./app/controller.php */