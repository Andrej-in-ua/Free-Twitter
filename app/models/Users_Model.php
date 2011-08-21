<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users_Model
{
	public static function getUserById($id)
	{
		$LZ = & get_instance();
		$stmt = $LZ->DB->prepare("SELECT * FROM `users` WHERE `id` = ? LIMIT 1");
		$stmt->execute(array($id));
		if ( $stmt->rowCount() != 1) return false;
		return $stmt->fetch(PDO::FETCH_OBJ);
	}
	
	public static function getUserByEmail($email)
	{
		$LZ = & get_instance();
		$stmt = $LZ->DB->prepare("SELECT `id`, `password`, `status` FROM `users` WHERE `email` = ? LIMIT 1");
		$stmt->execute(array($email));
		if ( $stmt->rowCount() != 1) return false;
		return $stmt->fetch(PDO::FETCH_OBJ);
	}
	
	public static function addUser($email, $pass, $name)
	{
		$LZ = & get_instance();
		$stmt = $LZ->DB->prepare("INSERT INTO `users` (`email`, `password`, `name`, `status`, `date`) VALUES (?, ?, ?, '2', NOW());");
		$stmt->execute(array($email, $pass, $name));
		return $LZ->DB->lastInsertId();
	}
	
	public static function statusUser($id, $status)
	{
		$LZ = & get_instance();
		return $LZ->DB->exec("UPDATE `users` SET `status` = '".$status."' WHERE `id` ='".$id."'");
	}
	
	public static function setUser($id, $arr)
	{
		$LZ = & get_instance();
		$update = NULL;
		foreach ($arr as $key => $val) $update .= (!is_null($update)?', ':'')."`".$key."` = '".$val."'";
		return $LZ->DB->exec("UPDATE `users` SET ".$update." WHERE `id` ='".$id."'");
	}
}

/* End of file users.php */
/* Location: ./app/models/users.php */