<?php
/**
 * User: Erik Wilson
 * Date: 13-Apr-17
 * Time: 21:23
 */
require_once "config.php";
session_start();
$dbh = db_connect() or die(ERR_MSG);

ob_start();
?>
<!--PAGE GOES HERE-->
<?php
ob_end_flush();
