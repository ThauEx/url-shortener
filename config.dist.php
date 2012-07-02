<?php
final class Config
{ 
	const STR_LENGTH = '10';					// Length of random string
	const SITE_URL = 'http://example.com/'; 

	const DB_TYPE = 'sqlite';					// sqlite or mysql

	const DB_HOST = 'localhost'; 				// Database host
	const DB_DATABASE = 'datebase';				// database name
	const DB_PORT = 3306; 
	const DB_USER = 'database_user'; 			// username
	const DB_PASSWORD = 'database_password';	// password

	const LOCAL_HOSTNAME = 'your.dyn.dns';		// clickout from this hostname won't be tracked
}
?>