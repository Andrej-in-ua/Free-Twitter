<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Flow_Model
{
	public static function addTwitt($user_id, $msg)
	{
		$LZ = & get_instance();
		$stmt = $LZ->DB->prepare("INSERT INTO `twitts` (`user_id`, `msg`, `date`) VALUES (?, ?, NOW());");
		$stmt->execute(array($user_id, $msg));
		return $LZ->DB->lastInsertId();
	}
	
	public static function delTwitt($id, $user_id)
	{
		$LZ = & get_instance();
		$stmt = $LZ->DB->prepare("DELETE FROM `twitts` WHERE `id` = ? AND `user_id` = ? LIMIT 1");
		$stmt->execute(array($id, $user_id));
		return $LZ->DB->lastInsertId();
	}
	
	public static function getTen($user_id, $start = 0)
	{
		
		$LZ = & get_instance();
		if ( $user_id == $_SESSION['user_id'] ){
			$stmt = $LZ->DB->prepare("SELECT `twitts`.*, `users`.`id` as user_id, `users`.`name`, `users`.`image`
				FROM `twitts`
				LEFT OUTER JOIN `following` ON `following`.`follower` = :userid
				LEFT OUTER JOIN `users` ON `twitts`.`user_id` = `users`.`id`
				WHERE `twitts`.`user_id` = :userid OR `twitts`.`user_id` = `following`.`following`
				ORDER BY `twitts`.`date` DESC LIMIT :start,10");
		} else {
			$stmt = $LZ->DB->prepare("SELECT `twitts`.*, `users`.`id` as user_id, `users`.`name`, `users`.`image`
				FROM `twitts`
				LEFT OUTER JOIN `users` ON `twitts`.`user_id` = `users`.`id`
				WHERE `twitts`.`user_id` = :userid
				ORDER BY `twitts`.`date` DESC LIMIT :start,10");
		}
		try {
			$stmt->bindParam(':userid', $user_id, PDO::PARAM_INT);
			$stmt->bindParam(':start', $start, PDO::PARAM_INT);
			$stmt->execute();
		} catch (PDOException $e) {
			echo 'Connection failed: ' . $e->getMessage();
		}
		return $stmt->fetchALL(PDO::FETCH_OBJ);
	}


	public static function addFollow($following, $follower)
	{
		$LZ = & get_instance();
		$stmt = $LZ->DB->prepare("INSERT INTO `following` (`following`, `follower`) VALUES (?, ?);");
		$stmt->execute(array($following, $follower));
		return $LZ->DB->lastInsertId();
	}
	
	public static function delFollow($following, $follower)
	{
		$LZ = & get_instance();
		$stmt = $LZ->DB->prepare("DELETE FROM `following` WHERE `following` = ? AND `follower` = ? LIMIT 1");
		return $stmt->execute(array($following, $follower));
	}
	
	public static function getFollow($following, $follower)
	{
		$LZ = & get_instance();
		$stmt = $LZ->DB->prepare("SELECT * FROM `following` WHERE `following` = ? AND `follower` = ? LIMIT 1");
		$stmt->execute(array($following, $follower));
		if ( $stmt->rowCount() != 1) return false;
		return $stmt->fetch(PDO::FETCH_OBJ);
	}
	
	public static function getFollowing($following)
	{
		$LZ = & get_instance();
		$stmt = $LZ->DB->prepare("SELECT `users`.`id`, `users`.`name`, `users`.`image` FROM `following` JOIN `users` ON `following`.`follower` = `users`.`id` WHERE `following` = ?");
		$stmt->execute(array($following));
		if ( $stmt->rowCount() == 0) return false;
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}
	
	public static function getFollower($follower)
	{
		$LZ = & get_instance();
		$stmt = $LZ->DB->prepare("SELECT `users`.`id`, `users`.`name`, `users`.`image` FROM `following` JOIN `users` ON `following`.`following` = `users`.`id` WHERE `follower` = ?");
		$stmt->execute(array($follower));
		if ( $stmt->rowCount() == 0) return false;
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}
}

/* End of file users.php */
/* Location: ./app/models/users.php */