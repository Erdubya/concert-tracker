<?php
/**
 * User: Erik Wilson
 * Date: 13-May-17
 * Time: 16:49
 */
require_once "paths.php";
require_once "_functions.php";

session_start();

// unset the session and delete the cookie
session_unset();
session_destroy();
setcookie("uid", "", time() - 3600);

header("Location: /login");
