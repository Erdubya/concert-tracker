<?php
/**
 * User: Erik Wilson
 * Date: 25-May-17
 * Time: 13:29
 */
require_once "config.php";
require_once "_functions.php";

// start the session and connect to DB
session_start();
$dbh = db_connect() or die(ERR_MSG);

$userid = $_SESSION['user'];

if (isset($_POST['update'])) {
    $stmt = $dbh->prepare("SELECT passwd FROM users WHERE user_id=?");
    $stmt->execute(array($userid));
    $result = $stmt->fetch();
    
    if (password_verify($_POST['curpass'], $result['passwd'])) {
        $sql  = "UPDATE users SET email=:email, name=:name WHERE user_id=:uid";
        $stmt = $dbh->prepare($sql);
        
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(":uid", $userid);

        $email = $_POST['email'];
        $name  = $_POST['name'];

        $stmt->execute();
        
        $_SESSION['username'] = $name;
        
        if ($_POST['newpass'] !== "" && ($_POST['newpass'] === $_POST['newpass-conf'])) {
            $sql  = "UPDATE users SET passwd=:passwd WHERE user_id=:uid";
            $stmt = $dbh->prepare($sql);
            
            $stmt->bindParam(":passwd", $passwd);
            $stmt->bindParam(":uid", $userid);
            
            $passwd = password_hash($_POST['newpass'], PASSWORD_DEFAULT);
            
            $stmt->execute();
        }
        
        header("Location: profile.php");
    } else {
        unset($_POST);
        die("PASSWORD incorrect");
//        header("Location: profile.php");
    }

} else {
    unset($_POST);
    die("UPDATE not set");
//    header("Location: profile.php");
}
