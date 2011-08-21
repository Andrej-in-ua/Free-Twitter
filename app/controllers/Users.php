<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends LZ_Controller
{
	function __construct()
	{
		// Get parent instance
		parent::__construct();
		$this->meta['page'] = 'user';
	}

	public function index()
	{
		if ( $_SESSION['login'] !== TRUE )	redirect('/users/login/');
									else	redirect('/users/profile/');
	}

/*
	Урощенный метод авторизации.
	Для проверки подлинности сессии проверяется только браузер и айпи
*/	
	public function login()
	{
		if ( $_SESSION['login'] === TRUE ) redirect('/users/profile/');
		
		if ( isset($_POST['login']) ) {
			$user = Users_Model::getUserByEmail($_POST['email']);
			if ( ! $user || $user->password != $this->hashPassword($_POST['password']) ) {
				$this->view("msg/error", array('content' => 'Введенные Вами логин+пароль недействительны'));
			} else {
				switch ( $user->status ) {
					case '2':
						$this->view("msg/error", array('content' => 'Ваш адресс почты еще не подтвержден'));
						break;
						
					case '0':
						$this->view("msg/error", array('content' => 'Доступ на сайт для вашего аккаунта ограничен'));
						break;
						
					case '1':
						$_SESSION['login'] = TRUE;
						$_SESSION['user_id'] = $user->id;
						$_SESSION['ip'] = $this->ip;
						$_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
						redirect('/');
						break;
				}
			}
		}
		
		$this->view('users/login');
	}

	public function logout() {
		$_SESSION['login'] = FALSE;
		unset($_SESSION['user_id']);
		unset($_SESSION['ip']);
		unset($_SESSION['user_agent']);
		$this->view("msg/ok", array('content' => 'Вы успешно вышли'));
		$this->view('users/login');
	}

	public function registration()
	{
		if ( isset($_POST['reg']) ) {
			// Проверяем обязательные поля
			if ( $_POST['name']  == NULL || $_POST['email']  == NULL )
			{
				$this->view("msg/error", array('content' => 'Не заполненны все обязательные поля'));
			}
			else if ( ! $this->isEmail($_POST['email']) )
			{
				$this->view("msg/error", array('content' => 'E-Mail адрес имеет неверный формат'));
			}
			else if ( ! preg_match('/^[a-z0-9\-_]{4,32}$/i', $_POST['name']) )
			{
				$this->view("msg/error", array('content' => 'Имя должно состоять из латинских символов и цифр, знака подчеркивания или тире'));
			}
			else if ( strlen($_POST['password']) < 6 )
			{ // Проверяем пароль
				$this->view("msg/error", array('content' => 'Пароль не должен быть короче 6 символов'));
			}
			else if ( $_POST['password'] != $_POST['password2'] )
			{
				$this->view("msg/error", array('content' => 'Введенные пароли не совпадают'));
			}
			else if ( strtolower($_POST['captcha']) != $_SESSION['code_reg'] )
			{
				$this->view("msg/error", array('content' => 'Введенные вами символы не совпадают с символами на картинке'));
			}
			else
			{
				$_POST['email'] = strtolower($_POST['email']);
				$password = $this->hashPassword($_POST['password']);
				// Заносим пользователя в базу
				$user_id = Users_Model::addUser($_POST['email'], $password, $_POST['name']);
				if ( $user_id < 1 )
				{
					$this->view("msg/error", array('content' => 'Данный адрес почты или имя пользователя уже используются'));
				}
				else
				{
					$host = parse_url(BASEURL);
					// Если все круто отправляем письмо с кодом
					$url = BASEURL."users/confirm/".$user_id."/".$this->confirmCode($password, $_POST['email']);
					mail($_POST['email'],
					"Пожалуйста, подтвердите свою регистрацию",
					"Пожалуйста, подтвердите свою регистрацию на сайте ".BASEURL."<br /><br />Имя указанное при регистрации: ".$_POST['name']."<br /><br />Ссылка для подтверждения регистрации:<br /><a href=\"".$url."\">".$url."</a>",
					"Content-type: text/html; charset=UTF-8\r\nFrom: Free Twitter <robot@".$host['host'].">\r\n");
					$this->view("msg/ok", array('content' => 'На почтовый ящик <strong>'.$_POST['email'].'</strong> отправлено письмо с кодом активации'));
					return;
				}
			}
			
		}
		$this->view('users/login');
	}
	
	public function confirm($user_id = false, $code = false) {
		$user_id = intval($user_id);
		if ( ! $user_id || ! $code || $user_id < 1) return $this->view('users/login');
		$user = Users_Model::getUserById($user_id);
		
		if ( $user->status != '2' ) return $this->view('users/login');
		
		if ( $this->confirmCode($user->password, $user->email) != $code ) {
			$this->view("msg/error", array('content' => 'Неверный код подтверждения'));
			return $this->view('users/login');
		}
		
		Users_Model::statusUser($user_id, '1');
		$this->view("msg/ok", array('content' => 'Теперь вы можете войти на сайт использую свой адрес почты и пароль'));
		$this->view('users/login');
	}
	
	public function profile($user_id = false)
	{
		$this->meta['page'] = 'profile';
		
		if ( ! $_SESSION['login'] ) return $this->view('users/login');
		if ( ! $user_id ) $user_id = $_SESSION['user_id'];
		$user = Users_Model::getUserById($user_id);
		
		if ( $user->id == $_SESSION['user_id']) {
			if ( isset($_POST['pas']) ) {
				if (  $user->password != $this->hashPassword($_POST['current']) ) {
					$this->view("msg/error", array('content' => 'Введенный Вами пароль неверный'));
				}
				else if ( strlen($_POST['password']) < 6 )
				{
					$this->view("msg/error", array('content' => 'Пароль не должен быть короче 6 символов'));
				}
				else if ( $_POST['password'] != $_POST['password2'] )
				{
					$this->view("msg/error", array('content' => 'Введенные пароли не совпадают'));
				} else {
					Users_Model::setUser($user->id, array( 'password' => $this->hashPassword($_POST['password'])) );
					$this->view("msg/ok", array('content' => 'Ваш пароль изменен'));
				}
			}
			
			if ( isset($_POST['img']) && isset($_FILES['photo']) && is_uploaded_file($_FILES['photo']['tmp_name']) )
			{
				if ( $_FILES['photo']['size'] > 716800 )	// 700kb
				{
					$this->view("msg/error", array('content' => 'Изображение сильно большое'));
				} else {
					list($name, $extention) = explode(".", $_FILES['photo']['name']);
					switch ($extention)
					{
						case 'png':		$mime = 'image/png'; break;
						case 'gif':		$mime = 'image/gif'; break;
						case 'jpg': 
						case 'jpeg':	
						default:		$mime = 'image/jpeg'; break;
					}
								
					if ( $_FILES['photo']['type'] != $mime )
					{
						$this->view("msg/error", array('content' => 'Упс, а мне не нравится ваша картинка ;)'));
					} else {
						$max_x = $max_y = 73;
						$size = getimagesize ($_FILES['photo']['tmp_name']);
						if ( $size[0] > $max_x || $size[1] > $max_y)
						{
							$k = max ($size[0] / $max_x, $size[1] / $max_y);
							$x_size = round ($size[0] / $k);
							$y_size = round ($size[1] / $k);
						} else {
							$x_size = $size[0];
							$y_size = $size[1];
						}
						
						$small_x = $small_y = 38;
						if ( $size[0] > $small_x || $size[1] > $small_y)
						{
							$k = max ($size[0] / $small_x, $size[1] / $small_y);
							$x_size_s = round ($size[0] / $k);
							$y_size_s = round ($size[1] / $k);
						} else {
							$x_size_s = $size[0];
							$y_size_s = $size[1];
						}
								
						$black_picture = imageCreateTrueColor ($x_size, $y_size);
						$preview = imageCreateTrueColor ($x_size_s, $y_size_s);
						
						@unlink("img/profile_images/profile_".$user->id.".".$user->image);
						@unlink("img/profile_images/profile_".$user->id."_small.".$user->image);
						$extention = NULL;
						switch ($_FILES['photo']['type'])
						{
							case 'image/jpeg':
								$extention = 'jpg';
								imagecopyresampled ($black_picture, imagecreatefromjpeg ($_FILES['photo']['tmp_name']), 0, 0, 0, 0, $x_size, $y_size, $size[0], $size[1]);
								imagejpeg ($black_picture, "img/profile_images/profile_".$user->id.".".$extention, 80);
								
								imagecopyresampled ($preview, imagecreatefromjpeg ($_FILES['photo']['tmp_name']), 0, 0, 0, 0, $x_size_s, $y_size_s, $size[0], $size[1]);
								imagejpeg ($preview, "img/profile_images/profile_".$user->id."_small.".$extention, 80);
								break;
							case 'image/png':
								$extention = 'png';
								imagealphablending ($black_picture, false);
								imagecopyresampled ($black_picture, imagecreatefrompng ($_FILES['photo']['tmp_name']), 0, 0, 0, 0, $x_size, $y_size, $size[0], $size[1]);
								imagesavealpha ($black_picture, true);
								imagepng ($black_picture, "img/profile_images/profile_".$user->id.".".$extention);
											
								imagealphablending ($preview, false);
								imagecopyresampled ($preview, imagecreatefrompng ($_FILES['photo']['tmp_name']), 0, 0, 0, 0, $x_size_s, $y_size_s, $size[0], $size[1]);
								imagesavealpha ($preview, true);
								imagepng ($preview, "img/profile_images/profile_".$user->id."_small.".$extention);
								break;
							case 'image/gif':
								$extention = 'gif';
								imagecopyresampled ($black_picture, imagecreatefromgif ($_FILES['photo']['tmp_name']), 0, 0, 0, 0, $x_size, $y_size, $size[0], $size[1]);
								imagegif ($black_picture, "img/profile_images/profile_".$user->id.".".$extention);
								
								imagecopyresampled ($preview, imagecreatefromgif ($_FILES['photo']['tmp_name']), 0, 0, 0, 0, $x_size_s, $y_size_s, $size[0], $size[1]);
								imagegif ($preview, "img/profile_images/profile_".$user->id."_small.".$extention);
								break;
						}
						
						if ( ! is_null($extention) ) {
							Users_Model::setUser($user->id, array( 'image' => $extention ) );
							$this->view("msg/ok", array('content' => 'Ваш аватар изменен'));
							$user->image = $extention;
						}
					}
				}
			}
		}
		$this->view('users/profile', (array) $user);
	}
	

// ------------------------------------------------------------------------

/**
 * Get hash from password
 *
 * @access	public
 * @param	string	the password
 * @return	string
 */	
	private function hashPassword($pw)
	{
		return md5('presol'.$pw.'posol');
	}

// ------------------------------------------------------------------------

/**
 * Get confirm code
 *
 * @access	public
 * @param	string	the password
 * @param	string	the email
 * @return	string
 */		
	private function confirmCode($pw, $email)
	{
		return md5('prefix'.$pw.'posol'.$email.'sufix');
	}
	
// ------------------------------------------------------------------------

/**
 * Email validation
 *
 * @access	public
 * @param	string	the email
 * @return	string
 */		
	private function isEmail($email)
	{
		if ( function_exists("filter_var") ){ // (PHP 5 >= 5.2.0)
			return filter_var($email, FILTER_VALIDATE_EMAIL);
		}
		return preg_match('/^[a-z0-9!#$%&*+-=?^_`{|}~]+(\.[a-z0-9!#$%&*+-=?^_`{|}~]+)*@([-a-z0-9]+\.)+([a-z]{2,3})$/ix', $email);
	}
}

/* End of file users.php */
/* Location: ./app/controllers/users.php */