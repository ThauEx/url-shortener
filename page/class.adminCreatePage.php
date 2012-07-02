<?php
require_once("class.adminPage.php");
class AdminCreatePage extends AdminPage
{
	public function run()
	{
		$this->page = "create";
		$this->title = "Kurz Url erstellen";
		$this->js = "";

		$this->_show();

		parent::run();
	}

	protected function _show()
	{
		if (isset($_POST['url']))
		{
			// Let's actually sanitize these entries!
			$_POST['tag'] = htmlentities($_POST['tag'], ENT_QUOTES);
			$_POST['note'] = htmlentities($_POST['note'], ENT_QUOTES);
			$_POST['url'] = htmlentities($_POST['url'], ENT_QUOTES);

			// Generate the random string and get the URL submitted
			if(isset($_POST['tag']) && $_POST['tag'] != "")
			{
				$random = trim($_POST['tag']);
			}

			else
			{
				$random = random_string(Config::STR_LENGTH);
			}

			$url = $_POST['url'];

			$queryParams = array( 
				':random' => strtolower($random)
			);

			$sqlQuery = "SELECT random
						 FROM urls 
						 WHERE lower(random) = :random"; 
			$stmt = Database::getInstance()->prepare($sqlQuery); 
			$stmt->execute($queryParams);

			if ($stmt->rowCount() > 0)
			{
				$siteUrl = config::SITE_URL;
				$this->return .= <<<EOT
		<p>Tag existiert bereits: <strong>{$random}</strong>, Bitte einen anderen wählen</p>
		<p><a href={$siteUrl}admin/create/'>Zurück</a></p>
EOT;
			}

			else
			{
				$siteUrl = config::SITE_URL;
				$this->return .= <<<EOT
		<p>Erstelle kurz URL mit der zufälligen Zeichenkette: <strong>{$random}</strong></p>
		<p>und wandel die URL um:<br /><span style="word-wrap: break-word" width="100">{$url}</span></p>
		<p>To the URL:<br /><a href="{$siteUrl}{$random}">{$siteUrl}{$random}</a></p>
EOT;

				$queryParams = array( 
					':random' => $random,
					':url' => $url,
					':ip' => $_SERVER['REMOTE_ADDR'],
					':note' => $_POST['note'],
				);

				$sqlQuery = "INSERT INTO urls (random, url, ip, note)
							 VALUES (:random, :url, :ip, :note)"; 
				$stmt = Database::getInstance()->prepare($sqlQuery);
				$stmt->execute($queryParams);

				$this->return .= '
		<div class="form-actions">
			<a class="btn btn-primary" href="' . config::SITE_URL . 'admin/create/">Neues Tag erstellen</a>
		</div>
				';
			}
		}

		else
		{
			$siteUrl = config::SITE_URL;
			$this->return .= <<<EOT
		<form class="form-horizontal" name="form" method="post" action="{$siteUrl}admin/create">
			<fieldset>
				<legend>Kurz Url erstellen</legend>
				<div class="control-group">
					<label class="control-label" for="inputUrl">Url</label>
					<div class="controls">
						<input type="text" class="input-xxlarge" id="inputUrl" name="url">
						<p class="help-block">Die lange URL, die verkürzt werden soll</p>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="inputTag">Tag</label>
					<div class="controls">
						<input type="text" class="input-xxlarge" id="inputTag" name="tag">
						<p class="help-block">Feld leer lassen, um einen zufälligen Tag zu generieren</p>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="inputNote">Notiz</label>
					<div class="controls">
						<input type="text" class="input-xxlarge" id="inputNote" name="note" maxlength="255">
						<p class="help-block">Optionale Notiz, die im Log neben der Url angezeigt wird</p>
					</div>
				</div>
				<div class="form-actions">
					<button class="btn btn-primary" type="submit">Speichern</button>
				</div>
			</fieldset>
		</form>

EOT;
		}
	}
}
?>