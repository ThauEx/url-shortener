<?php
class Database { 
	private static $db;

	static public function getInstance()
	{
		if(!self::$db)
		{
			try
			{
				self::$db = new PDO(
					'mysql:host=' . Config::DB_HOST . ';dbname=' . Config::DB_DATABASE.';port=' . Config::DB_PORT,
					Config::DB_USER,
					Config::DB_PASSWORD,
					array(
						PDO::ATTR_PERSISTENT => true,
						PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
						PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
					)
				);
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