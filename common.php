<?php
error_reporting(E_ALL);

require_once("lib/class.kint.php");

if (filesize(dirname(__FILE__) . '/config.php') == 0)
{
	echo 'Not installed!';
	exit();
}

// Include configuration settings
include('config.php');
require_once('lib/class.' . Config::DB_TYPE . '.php');

Database::getInstance();

// Function for creating tiny URL string
function random_string($len = "4")
{
	$str = '';
	$last_char = '';

	for ($i = 0; $i < $len; $i++)
	{
		$char = chr(rand(48, 122));

		while (!preg_match("#[a-zA-Z0-9]#", $char))
		{
			if ($char == $last_char)
			{
				continue;
			}

			$char = chr(rand(48, 90));
		}

		$str .= $char;
		$last_char = $char;

	}

	return $str;
}
?>