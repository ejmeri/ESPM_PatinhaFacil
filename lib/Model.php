<?php

namespace lib;

use helper\Database;

Class Model extends Database
{
	protected $db;
	
	public function __construct()
	{	
		$this->db = new Database();
	}
}
?>