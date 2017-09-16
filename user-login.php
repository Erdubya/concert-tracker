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
    //Get user info based off input email
    $stmt = $dbh->prepare("SELECT user_id, passwd, name FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $email = $_POST['email'];
    $stmt->execute();
    $result = $stmt->fetchAll();
    
    // Ensure only one row is returned
    if (count($result) != 1) {
        unset($_POST);
        header("Location: login.php");
    } else {
        $result = $result[0];
    }
    
    // Verify the password
    if (password_verify($_POST['password'], $result['passwd'])) {
        // Set the session data
        $_SESSION['user'] = $result['user_id'];
        $_SESSION['username'] = $result['name'];
        
        // Setup persistent login if requested
        if (isset($_POST['remember'])) {
            // Generate tokens
            $selector = gen_token(6);
            $token    = gen_token(32);
            
            // Set cookie
            $value    = $selector . ":" . $token;
            $expires  = time() + (86400 * 30); // 30 day expiry
            setcookie("uid", $value, $expires, "/");

            // Store token data in database
            $stmt = $dbh->prepare("INSERT INTO auth_token(selector, token, user_id, expires) VALUES(:selector, :token, :userid, :expires)");
            $stmt->bindParam(":selector", $selector);
            $stmt->bindParam(":token", $hashToken);
            $stmt->bindParam(":userid", $_SESSION['user']);
            $stmt->bindParam(":expires", $formatExp);
            
            $hashToken = hash("sha256", $token);
            $formatExp = date("Y-m-d H:i:s", $expires);
            
            $stmt->execute() or die();
        }
        header("Location: index.php");
    } else {
        unset($_POST);
        header("Location: login.php");
    }
    
} else {
    unset($_POST);
    header("Location: login.php");
}
