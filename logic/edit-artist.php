<?php
/**
 * User: Erik Wilson
 * Date: 18-Apr-17
 * Time: 13:17
 */
//require the config file
require_once "../config.php";

// start the session and connect to DB
session_start();
$dbh = db_connect() or die(ERR_MSG);

$stmt = $dbh->prepare("UPDATE artist SET name=:artist, genre=:genre, country=:country WHERE artist_id=:id");
$stmt->bindParam(':artist', $name);
$stmt->bindParam(':genre', $genre);
$stmt->bindParam(':country', $country);
$stmt->bindParam(':id', $id);

$name    = $_POST['artist'];
$genre   = $_POST['genre'];
$country = $_POST['country'];
$id      = (int) $_POST['id'];

$stmt->execute();

//var_dump($name);
//var_dump($genre);
//var_dump($country);
//var_dump($id);

unset($_POST);
header('Location: ../artists.php');
