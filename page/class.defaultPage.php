<?php
require_once("class.page.php");
class DefaultPage extends Page
{
	public function run()
	{
		if (isset($this->params))
		{
			$random = $this->params;

			$queryParams = array( 
				':random' => $random
			);

			$sqlQuery = "SELECT COUNT(*)
						FROM urls
						WHERE random = :random"; 
			$stmt = Database::getInstance()->prepare($sqlQuery); 
			$stmt->execute($queryParams);

			if ($stmt->fetchColumn() == 0)
			{
				// The requested name was not found in the database
				//echo 'URL not found';
				header('HTTP/1.1 403 Forbidden');
			}

			else
			{
				$sqlQuery = "SELECT url
							FROM urls
							WHERE random = :random"; 
				$stmt = Database::getInstance()->prepare($sqlQuery); 
				$stmt->execute($queryParams);

				$row = $stmt->fetch();

				$url = $row['url'];

				if ($_SERVER["REMOTE_ADDR"] != gethostbyname(config::LOCAL_HOSTNAME))
				{
					$sqlQuery = "UPDATE urls
								SET hits = hits+1
								WHERE random = :random"; 
					$stmt = Database::getInstance()->prepare($sqlQuery); 
					$stmt->execute($queryParams);
				}
				
				if (!empty($url)) {
					header("Location: $url");
				}

				else
				{
					header('HTTP/1.1 403 Forbidden');
				}
				
			}
		}
	}
}

?>