<?php
/**
 * User: Erik Wilson
 * Date: 17-Apr-17
 * Time: 13:08
 */
//require the config file
require_once "config.php";
require_once "_functions.php";

// start the session and connect to DB
session_start();
$dbh = db_connect() or die(ERR_MSG);

if (isset($_POST['submit'])) {
    $stmt = $dbh->prepare("INSERT INTO artist(user_id, name, genre, country) VALUES (:userid, :artist, :genre, :country)");
    $stmt->bindParam(":userid", $user);
    $stmt->bindParam(':artist', $name);
    $stmt->bindParam(':genre', $genre);
    $stmt->bindParam(':country', $country);

    $user    = $_SESSION['user'];
    $name    = $_POST['artist_name'];
    $genre   = $_POST['genre'];
    $country = $_POST['country'];

    $stmt->execute();
}
unset($_POST);
header('Location: /artists');
