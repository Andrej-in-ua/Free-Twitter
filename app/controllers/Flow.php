<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Flow extends LZ_Controller
{
	function __construct()
	{
		// Get parent instance
		parent::__construct();
		
		$this->meta['page'] = 'flow';
	}

	public function index()
	{
		if ( ! $_SESSION['login'] )
		{
			redirect('users');
		}
		return $this->user($_SESSION['user_id']);
	}
	
	public function user($user_id = false)
	{
		if ( ! $user_id ) redirect('/flow');
		$user_id = intval($user_id);

		// $data['flow'] = Flow_Model::getTen($user_id);
		$data['user'] = Users_Model::getUserById($user_id);
		
		if ( ! $data['following'] = Flow_Model::getFollower($user_id) ) $data['following'] = array();
		if ( ! $data['follower'] = Flow_Model::getFollowing($user_id) ) $data['follower'] = array();
		
		
		if ( $user_id == $_SESSION['user_id'] ){
			$data['cur'] = true;
		} else {
			$data['cur'] = false;
			
			$this->meta['page'] = 'flow/user/'.$user_id;
			$this->meta['tabs']['flow/'] = '&lt; Назад';
			$this->meta['tabs']['flow/user/'.$user_id] = $data['user']->name;
		
			if ( ! Flow_Model::getFollow($user_id, $_SESSION['user_id']) ) {
				$data['follow'] = FALSE;
			} else {
				$data['follow'] = TRUE;
			}
		}
		$this->view('flow/flow', $data);
	}
	
	
	public function following($user_id = false)
	{
		if ( ! $user_id || ! $_SESSION['user_id']) redirect('/flow');
		$user_id = intval($user_id);
		
		$user = Users_Model::getUserById($user_id);
		if ( ! Flow_Model::getFollow($user_id, $_SESSION['user_id']) ) {
			Flow_Model::addFollow($user_id, $_SESSION['user_id']);
			$this->view("msg/ok", array('content' => 'Теперь сообщения пользователя '.$user->name.' вы сможете читать в своем потоке'));
		} else {
			$this->view("msg/error", array('content' => 'Вы уже читаете сообщения пользователя '.$user->name.' в своем потоке'));
		}
		return $this->user($_SESSION['user_id']);
	}
	
	public function unfollowing($user_id = false)
	{
		if ( ! $user_id || ! $_SESSION['user_id']) redirect('/flow');
		$user_id = intval($user_id);
		
		$user = Users_Model::getUserById($user_id);
		if ( Flow_Model::delFollow($user_id, $_SESSION['user_id']) ) {
			$this->view("msg/ok", array('content' => 'Теперь вы не читаете сообщения пользователя '.$user->name.' в своем потоке'));
		}
		return $this->user($_SESSION['user_id']);
	}
	
	
	
	public function nextten($user_id = false, $start = false)
	{
		$this->return_page = false;
		$arr['status'] = true;
		
		$user_id = intval($_POST['user_id']);
		$start = intval($_POST['start']);
		
		if ( ! $user_id || ( ! $start && $start != 0 )  ) $this->ajax_die('404');

		$arr['flow'] = Flow_Model::getTen($user_id, $start);
		die(json_encode($arr));
	}
	
	public function add()
	{
		$this->return_page = false;
		$arr['status'] = true;
		
		if ( ! isset($_POST['msg']) || ! $_SESSION['login'] ) $this->ajax_die('404');
		
		$_POST['msg'] = trim(nl2br(htmlspecialchars($_POST['msg'])));
		$arr['msg_id'] = Flow_Model::addTwitt($_SESSION['user_id'], $_POST['msg']);
		$arr['date'] = date("Y-m-d H:i:s");
		$arr['msg'] = $_POST['msg'];
		 
		
		die(json_encode($arr));
	}
	
	public function del()
	{
		$this->return_page = false;
		$arr['status'] = true;

		$id = intval($_POST['twitt_id']);
		Flow_Model::delTwitt($id, $_SESSION['user_id']);
				
		die(json_encode($arr));
	}
	
	private function ajax_die($error, $arr = array()){
		$arr['status'] = 'false';
		$arr['error'] = $error;
		die(json_encode($arr));
	}
}
/* End of file flow.php */
/* Location: ./app/controllers/flow.php */