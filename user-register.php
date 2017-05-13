<?php
/**
 * User: Erik Wilson
 * Date: 13-May-17
 * Time: 18:14
 */
require_once "config.php";
require_once "_functions.php";
require_once "lib/random_compat.phar";

// start the session and connect to DB
session_start();
$dbh = db_connect() or die(ERR_MSG);

if (isset($_POST['register'])) {
    
} else {
    unset($_POST);
    header("Location: register.php");
}
