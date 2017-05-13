<?php
/**
 * User: Erik Wilson
 * Date: 13-May-17
 * Time: 16:49
 */
require_once "config.php";
require_once "_functions.php";

session_start();

// unset the session and delete the cookie
unset($_SESSION);
setcookie("uid", "", time() - 3600);

header("Location: login.php");
