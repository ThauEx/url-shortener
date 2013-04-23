<?php
class Dispatcher {
	function __construct() {
		$requestedUrl = $this->getcurrentPageUrl();
		$url = str_replace(Config::SITE_URL, "", $requestedUrl);

		$url = preg_replace('#^/(.*)#', "$1", $url );

		// remvoe get params
		$urlData = explode("?", $url);
		$urlPart = $urlData[0];
		$queryPart = "";
		$queryParms = array();

		if(count($urlData) > 1)
		{
			$queryPart = $urlData[1];
		}

		// parse url part
		$tempUrlParts = explode("/", $urlPart);
		$tempUrlParts = array_filter($tempUrlParts);
		$action = "";
		$param = array();
		$value = array();
		$hasNoParam = substr($url, -1) == "/";

		if (count($tempUrlParts) )
		{
			$urlParamIncrement = 0;
			$mod1 = 0;
			$mod2 = 1;

			if (count($tempUrlParts) == 1 && $hasNoParam)
			{
				$action = $tempUrlParts[0];

				$urlParamIncrement = 1;
				$mod1 = 1;
				$mod2 = 0;
			}

			elseif (count($tempUrlParts) > 2 || $hasNoParam)
			{
				$urlParamIncrement = 2;
				$action = $tempUrlParts[0] . ucfirst($tempUrlParts[1]);
			}

			for($j = 0, $k = 0;$urlParamIncrement < count($tempUrlParts);$urlParamIncrement++)
			{
				if ($urlParamIncrement%2 == $mod1)
				{
					$param[$j] = $tempUrlParts[$urlParamIncrement];
					$j++;
				}

				elseif ($urlParamIncrement%2 == $mod2)
				{
					$value[$k] = $tempUrlParts[$urlParamIncrement];
					$k++;
				}
			}
		}

		// parse query params
		if (count($urlData) > 1)
		{
			$queryPart = $urlData[1];
			 
			$tempQueryParts = explode("&", $queryPart);
			foreach($tempQueryParts as $q)
			{
				$temp = explode("=", $q);
				$key = $temp[0];
				$value[] = $temp[1];
				$queryParms[$key] = $value;
			}
		}

		$parms = array(
			"action" => $action,
			"param" => $param,
			"value" => $value,
			"get" => $queryParms,
			"post" => $_POST
		);

		try
		{
			if (empty($parms["action"]))
			{
				$parms["action"] = "default";
				$parms["param"] = reset($parms["param"]);
				$parms["value"] = array();
			}

			if (file_exists("page/class." . $parms["action"] . "Page.php"))
			{
				require_once("page/class." . $parms["action"] . "Page.php");
				$className = $parms["action"] . "Page";
				if (class_exists($className))
				{
					new $className($parms["action"], $parms["param"], $parms["value"]);
				}

				else
				{
					Kint::dump($parms);
					throw new Exception ("The class " . $parms["action"] . "Page does not exist!");
				}
			}

			else
			{
				Kint::dump($parms);
				throw new Exception ("The file class." . $parms["action"] . "Page.php does not exist!");
			}
		}

		catch (Exception $e)
		{
			echo $e->getMessage(), "\n";
		}
	}

	function getcurrentPageUrl()
	{
		$pageURL = 'http';
		if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on")
		{
			$pageURL .= "s";
		}

		$pageURL .= "://";
		if (in_array($_SERVER["SERVER_PORT"], array("80", "443")))
		{
			$pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
		}

		else
		{
			$pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
		}

		return $pageURL;
	}
}
?>