<?php
/**
 * User: Erik Wilson
 * Date: 13-Apr-17
 * Time: 21:23
 */
// check if installed
if (!file_exists('config.php')) {
    die("Please run the <a href='install.php'>install script</a> set up Concert Tracker.");
}

require_once "config.php";

session_start();
$dbh = db_connect() or die(ERR_MSG);

ob_start();
echo "Page is loaded";
?>
<!--PAGE GOES HERE-->
<?php
ob_end_flush();
