<?php
require_once("class.adminPage.php");
class AdminLogPage extends AdminPage
{
	public function run()
	{
		$this->page = "log";
		$this->title = "URLs in der Datenbank";
		$this->js = '<script type="text/javascript" src="' . config::SITE_URL . 'static/js/jquery.tablesorter.js"></script>';

		if (empty($this->params))
		{
			$this->_show();
		}

		else
		{
			switch($this->params[0])
			{
				case "remove":
					$this->_remove();
				break;

				case "edit":
					$this->_edit();
				break;

				case "show":
				default:
					$this->_show();
				break;
			}
		}

		parent::run();
	}

	protected function _remove()
	{
		if (empty($this->values[0]))
		{
			$this->return = "ID ist nicht vorhanden";
		}

		else
		{
			$queryParams = array( 
				':id' => $this->values[0]
			);

			$sqlQuery = "SELECT COUNT(*)
						 FROM urls
						 WHERE id = :id";
			$stmt = Database::getInstance()->prepare($sqlQuery); 
			$stmt->execute($queryParams);

			if ($stmt->fetchColumn() == 0)
			{
				$this->return = "ID ist nicht vorhanden";
			}

			else
			{
				$sqlQuery = "DELETE FROM urls
							 WHERE id = :id"; 
				$stmt = Database::getInstance()->prepare($sqlQuery); 
				$stmt->execute($queryParams);

				$this->return = "<div class='alert alert-success'>ID " . $this->values[0] . " erfolgreich gelöscht. <a href='" . config::SITE_URL . "admin/log/'>Weiter &raquo;</a></div>";
			}
		}
	}

	protected function _edit()
	{
		if (empty($this->values[0]))
		{
			$this->return = "ID ist nicht vorhanden";
		}

		else
		{
			if (isset($_POST['edit']))
			{
				$queryParams = array( 
					':id' => $this->values[0],
					':url' => $_POST['url'],
					':note' => $_POST['note'],
					':random' => $_POST['tag'],
					':hits' => $_POST['hits']
				);

				$sqlQuery = "UPDATE urls
							 SET url = :url,
								 note = :note,
								 random = :random,
								 hits = :hits
							 WHERE id = :id "; 
				$stmt = Database::getInstance()->prepare($sqlQuery); 
				$stmt->execute($queryParams);

				$this->return = "<div class='alert alert-success'>ID " . $this->values[0] . " erfolgreich aktualisiert. <a href='" . config::SITE_URL . "admin/log/'>Weiter &raquo;</a></div>";
			}

			else
			{
				$queryParams = array( 
					':id' => $this->values[0]
				);

				$sqlQuery = "SELECT COUNT(*)
							 FROM urls
							 WHERE id = :id";
				$stmt = Database::getInstance()->prepare($sqlQuery); 
				$stmt->execute($queryParams);

				if ($stmt->fetchColumn() == 0)
				{
					$this->return = "ID ist nicht vorhanden";
				}

				else
				{
					$sqlQuery = "SELECT *
								 FROM urls
								 WHERE id = :id";
					$stmt = Database::getInstance()->prepare($sqlQuery); 
					$stmt->execute($queryParams);

					while ($row = $stmt->fetch())
					{
						$siteUrl = config::SITE_URL;
						$this->return .= <<<EOT
			<form class="form-horizontal" name="form" method="post" action="{$siteUrl}admin/log/edit/{$this->values[0]}">
				<input type="hidden" name="edit" value="edit" />
				<fieldset>
					<legend>Kurz Url bearbeiten</legend>
					<div class="control-group">
						<label class="control-label" for="inputUrl">Url</label>
						<div class="controls">
							<input type="text" class="input-xxlarge" id="inputUrl" name="url" value="{$row['url']}">
							<p class="help-block">Die lange URL, die verkürzt werden soll</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="inputTag">Tag</label>
						<div class="controls">
							<input type="text" class="input-xxlarge" id="inputTag" name="tag" value="{$row['random']}">
							<p class="help-block">Feld leer lassen, um einen zufälligen Tag zu generieren</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="inputNote">Notiz</label>
						<div class="controls">
							<input type="text" class="input-xxlarge" id="inputNote" name="note" maxlength="255" value="{$row['note']}">
							<p class="help-block">Optionale Notiz, die im Log neben der Url angezeigt wird</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="inputHits">Hits</label>
						<div class="controls">
							<input type="text" class="input-xxlarge" id="inputHits" name="hits" value="{$row['hits']}">
							<p class="help-block">Anzahl der Klicks</p>
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
		}
	}

	protected function _show()
	{
		$this->return .= <<<EOT
		<h2>URLs in der Datenbank</h2>

		<table class="table table-bordered table-striped table-sorter" id="logtable">
			<thead>
				<tr>
					<th>ID</th>
					<th>Tag</th>
					<th>Hits</th>
					<th>URL</th>
					<!--<th>IP</th>-->
					<th>Notiz</th>
					<th>Bearbeiten</th>
					<th>Löschen</th>
				</tr>
			</thead>
			<tbody>

EOT;

		$queryParams = array();

		$sqlQuery = "SELECT COUNT(*)
					 FROM urls"; 
		$stmt = Database::getInstance()->prepare($sqlQuery); 
		$stmt->execute($queryParams);

		if ($stmt->fetchColumn() == 0)
		{
			$this->return .= <<<EOT
				<tr>
					<td colspan="5">Keine URLs in der Datenbank.</td>
				</tr>

EOT;
		}

		else
		{
			$sqlQuery = "SELECT *
						 FROM urls
						 ORDER BY id ASC"; 
			$stmt = Database::getInstance()->prepare($sqlQuery); 
			$stmt->execute($queryParams);

			$count = 1;
			while ($row = $stmt->fetch())
			{
				$siteUrl = config::SITE_URL;
				$this->return .= <<<EOT
				<tr>
					<td>{$row['id']}</td>
					<td class="tag"><a href="{$siteUrl}{$row['random']}" target="_blank">{$row['random']}</a></td>
					<td>{$row['hits']}</td>
					<td class="overflow"><a href="{$row['url']}" target="_blank" title="{$row['url']}">{$row['url']}</a></td>
					<!--<td><a href="http://www.whois.sc/{$row['ip']}">{$row['ip']}</a></td>-->
					<td class="note"><span title="{$row['note']}">{$row['note']}</span></td>
					<td class="edit"><a href="{$siteUrl}admin/log/edit/{$row['id']}">Bearbeiten</a></td>
					<td class="remove"><a href="{$siteUrl}admin/log/remove/{$row['id']}">Löschen</a></td>
				</tr>

EOT;
				$count++;
			}
		}

		$this->return .= <<<EOT
			</tbody>
		</table>

EOT;
	}
}
?>