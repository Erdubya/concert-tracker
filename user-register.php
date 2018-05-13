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
    // set up SQL statement
    $stmt = $dbh->prepare("INSERT INTO users(email, passwd, name) VALUES (:email, :passwd, :name)");
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":passwd", $passwd);
    $stmt->bindParam(":name", $name);

    $email  = trim($_POST['email']);
    $passwd = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $name   = trim($_POST['name']);

    $stmt->execute();

    header("Location: /login");
} else {
    unset($_POST);
    header("Location: /register");
}
