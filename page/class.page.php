<?php
abstract class Page
{
	protected $action = null;
	protected $params = null;
	protected $values = null;

	function __construct($action = "", $params = array(), $values = array())
	{
		$this->action = $action;
		$this->params = $params;
		$this->values = $values;
		$this->run();
	}

	abstract function run();
}