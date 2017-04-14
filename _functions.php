<?php
/**
 * User: Erik Wilson
 * Date: 13-Apr-17
 * Time: 21:23
 */

/** Function to connect to a database
 * Uses settings provided in _configuration.php
 * @return null|PDO
 */
function db_connect()
{
	try {
		$dbh = new PDO(HANDLER . ":host=" . HOSTNAME . ";dbname=" . DATABASE, USERNAME, PASSWORD);
	} catch (PDOException $e) {
		$dbh = null;
	}
	
	return $dbh;
}

function create_db($dbh)
{
	//create the database
}
