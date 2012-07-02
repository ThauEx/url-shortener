<?php
class Database { 
	private static $db;

	static public function getInstance()
	{
		if(!self::$db)
		{
			try
			{
				self::$db = new PDO("sqlite:" . dirname(__FILE__) . "/../db.sqlite");
				self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			}

			catch(PDOException $ex)
			{
				header('HTTP/1.1 500 Internal Server Error');
				exit('Unable to connect');
			}
		}

		return self::$db;
	}
}
?>