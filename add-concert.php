<?php
/**
 * User: Erik Wilson
 * Date: 17-Apr-17
 * Time: 13:10
 */
//require the config file
require_once "config.php";
require_once "_functions.php";

// start the session and connect to DB
session_start();
$dbh = db_connect() or die(ERR_MSG);

if (isset($_POST['submit'])) {
    $stmt = $dbh->prepare("INSERT INTO concert(artist, date, city, attend) VALUES (:artist, :showdate, :city, :attend)");
    $stmt->bindParam(':artist', $artist);
    $stmt->bindParam(':showdate', $date);
    $stmt->bindParam(':city', $city);
    $stmt->bindParam(':attend', $attend);

    $artist = $_POST['artist_id'];
    $date   = $_POST['date'];
    $city   = $_POST['city'];
    if (isset($_POST['attend'])) {
        $attend = 1;
    } else {
        $attend = 0;
    }

    $stmt->execute();
}

unset($_POST);
header('Location: concerts.php');
