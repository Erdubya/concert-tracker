<?php
/**
 * User: Erik Wilson
 * Date: 18-Apr-17
 * Time: 13:17
 */
//require the config file
require_once "config.php";
require_once "_functions.php";

// start the session and connect to DB
session_start();
$dbh = db_connect() or die(ERR_MSG);

if (isset($_POST['submit'])) {
// Prepare the sql statement
    $stmt = $dbh->prepare("UPDATE artist SET name=:artist, genre=:genre, country=:country WHERE artist_id=:id");
    $stmt->bindParam(':artist', $name);
    $stmt->bindParam(':genre', $genre);
    $stmt->bindParam(':country', $country);
    $stmt->bindParam(':id', $id);

// Assign the variables
    $name    = $_POST['artist'];
    $genre   = $_POST['genre'];
    $country = $_POST['country'];
    $id      = (int) $_POST['id'];

// Execute the statement
    $stmt->execute();
}

// clear POST and return to the calling page
unset($_POST);
header('Location: /artists');
