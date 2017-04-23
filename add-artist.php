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

$stmt = $dbh->prepare("INSERT INTO artist(name, genre, country) VALUES (:artist, :genre, :country)");
$stmt->bindParam(':artist', $name);
$stmt->bindParam(':genre', $genre);
$stmt->bindParam(':country', $country);

$name    = $_POST['artist_name'];
$genre   = $_POST['genre'];
$country = $_POST['country'];

$stmt->execute();

unset($_POST);
header('Location: artists.php');
