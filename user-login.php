<?php
/**
 * User: Erik Wilson
 * Date: 13-May-17
 * Time: 01:35
 */
require_once "config.php";
require_once "_functions.php";

// start the session and connect to DB
session_start();
$dbh = db_connect() or die(ERR_MSG);

if (isset($_POST['login'])) {
    $stmt = $dbh->prepare("SELECT user_id, passwd FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    
    $email = $_POST['email'];
    
    $stmt->execute();
    
    $result = $stmt->fetchAll();
    
    if (count($result) != 1) {
        unset($_POST);
        header("Location: login.php");
    }
    
    if (password_verify($_POST['password'], $result['passwd'])) {
        $_SESSION['user'] = $result['user_id'];
        header("Location: index.php");
        // TODO: cookies
    } else {
        unset($_POST);
        header("Location: login.php");
    }
    
} else {
    header("Location: login.php");
}
