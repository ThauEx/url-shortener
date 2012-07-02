<?php
require_once("class.page.php");
class AdminPage extends Page
{
	public $return = "";
	public $title = "";
	public $js = "";
	public $page = "";

	public function run()
	{
		if ($this->action == "admin")
		{
			header("Location: " . config::SITE_URL . "admin/log/");
		}

		else
		{
			$this->_renderer();			
		}
	}

	protected function _renderer()
	{ ?>
<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="utf-8">
<title><?php echo $this->title; ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link type="text/css" href="<?php echo config::SITE_URL; ?>static/css/style.css" rel="stylesheet" />
<link type="text/css" href="<?php echo config::SITE_URL; ?>static/css/bootstrap.css" rel="stylesheet">
<link type="text/css" href="<?php echo config::SITE_URL; ?>static/css/bootstrap-responsive.css" rel="stylesheet">
<script type='text/javascript' src='<?php echo config::SITE_URL; ?>static/js/jquery.js'></script> 
<script type='text/javascript' src='<?php echo config::SITE_URL; ?>static/js/bootstrap.min.js'></script> 
<?php echo $this->js; ?>
</head>
<body>
	<div class="navbar navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container">
				<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</a>
				<a class="brand" href="<?php echo  config::SITE_URL; ?>admin/">ShortUrl</a>
				<div class="nav-collapse">
					<ul class="nav">
						<li <?php if($this->page == "log") { ?>class="active"<?php } ?>><a href="<?php echo  config::SITE_URL; ?>admin/log/">Log</a></li>
						<li <?php if($this->page == "create") { ?>class="active"<?php } ?>><a href="<?php echo  config::SITE_URL; ?>admin/create/">Erstellen</a></li>
					</ul>
				</div><!--/.nav-collapse -->
			</div>
		</div>
	</div>
	<div class="container">
<?php echo $this->return; ?>
	</div> <!-- /container -->
</body>
</html>
<?php
	}
}
?>