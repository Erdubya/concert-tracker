<?php
/**
 * User: Erik Wilson
 * Date: 13-May-17
 * Time: 01:35
 */
require_once "config.php";
require_once "_functions.php";
require_once "lib/random_compat.phar";

// start the session and connect to DB
session_start();
$dbh = db_connect() or die(ERR_MSG);

if (isset($_POST['login'])) {
    $stmt = $dbh->prepare("SELECT user_id, passwd, name FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    
    $email = $_POST['email'];
    
    $stmt->execute();
    
    $result = $stmt->fetchAll();
    
    if (count($result) != 1) {
        unset($_POST);
        header("Location: login.php");
    } else {
        $result = $result[0];
    }
    
    if (password_verify($_POST['password'], $result['passwd'])) {
        $_SESSION['user'] = $result['user_id'];
        $_SESSION['username'] = $result['name'];
        
        $selector = random_bytes(12);
        $token = random_bytes(64);
        $value = $selector . ":" . $token;
        $expires = time() + (86400 * 30); // 30 day expiry
        
        setcookie("uid", $value, $expires, "/");
        
        $stmt = $dbh->prepare("INSERT INTO auth_token(selector, token, user_id, expires) VALUES(:selector, :token, :userid, :expires)");
        $stmt->bindParam(":selector", $selector);
        $stmt->bindParam(":token", hash("sha256", $token));
        $stmt->bindParam(":userid", $_SESSION['user']);
        $stmt->bindParam(":expires", date("c", $expires));
        
        try {
            $stmt->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        header("Location: index.php");
    } else {
        unset($_POST);
        header("Location: login.php");
    }
    
} else {
    header("Location: login.php");
}
