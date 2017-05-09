<?php
/**
 * User: Erik Wilson
 * Date: 18-Apr-17
 * Time: 17:14
 */
//require the config file
require_once "config.php";
require_once "_functions.php";

// start the session and connect to DB
session_start();
$dbh = db_connect() or die(ERR_MSG);

if (isset($_POST['update'])) {
    $stmt = $dbh->prepare("UPDATE concert SET artist=:artist, date=:showdate, city=:city, attend=:attend, notes=:notes WHERE concert_id=:id");
    $stmt->bindParam(':artist', $artist);
    $stmt->bindParam(':showdate', $date);
    $stmt->bindParam(':city', $city);
    $stmt->bindParam(':attend', $attend);
    $stmt->bindParam(':notes', $notes);
    $stmt->bindParam(':id', $id);

    $artist = (int) $_POST['artist'];
    $date   = $_POST['date'];
    $city   = $_POST['city'];
    $notes  = $_POST['notes'];
    $id     = (int) $_POST['id'];
    if (isset($_POST['attend'])) {
        $attend = 1;
    } else {
        $attend = 0;
    }

    $stmt->execute();
} elseif (isset($_POST['delete'])) {
    $stmt = $dbh->prepare("DELETE FROM concert WHERE concert_id=:id");
    
    $stmt->bindParam(':id', $id);
    
    $id = (int) $_POST['id'];
    
    $stmt->execute();
}

//var_dump($artist);
//var_dump($date);
//var_dump($city);
//var_dump($notes);
//var_dump($id);
//var_dump($attend);


unset($_POST);
header('Location: concerts.php');
