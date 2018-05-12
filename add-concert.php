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

//var_dump($_POST);
//die();

if (isset($_POST['add'])) {
    $stmt = $dbh->prepare("INSERT INTO concert(date, city, attend, notes) VALUES (:showdate, :city, :attend, :notes)");
//    $stmt->bindParam(':artist', $artist);
    $stmt->bindParam(':showdate', $date);
    $stmt->bindParam(':city', $city);
    $stmt->bindParam(':attend', $attend);
    $stmt->bindParam(':notes', $notes);

//    $artist = $_POST['artist_id'];
    $date   = $_POST['date'];
    $city   = $_POST['city'];
    $notes  = $_POST['notes'];
    if (isset($_POST['attend'])) {
        $attend = 1;
    } else {
        $attend = 0;
    }

    $stmt->execute();

    $concertid = $dbh->lastInsertId();
    add_new_artists($dbh, $concertid, $_POST['p_artist'], $_POST['o_artist']);
}

/**
 * @param $dbh        PDO
 * @param $concert_id int
 * @param $p_artists  array
 * @param $o_artists  array
 */
function add_new_artists($dbh, $concert_id, $p_artists, $o_artists)
{
    $stmt = $dbh->prepare("INSERT INTO concert_artists(artist_id, concert_id, is_primary) VALUES (:aid, :cid, :isp)");
    $stmt->bindParam(':aid', $artist_id);
    $stmt->bindParam(':cid', $concert_id);
    $stmt->bindParam(':isp', $is_primary);

    foreach ($p_artists as $a) {
        /** @noinspection PhpUnusedLocalVariableInspection */
        $artist_id  = $a;
        /** @noinspection PhpUnusedLocalVariableInspection */
        $is_primary = true;
        $stmt->execute();
    }
    foreach ($o_artists as $a) {
        /** @noinspection PhpUnusedLocalVariableInspection */
        $artist_id  = $a;
        /** @noinspection PhpUnusedLocalVariableInspection */
        $is_primary = false;
        $stmt->execute();
    }
}

unset($_POST);
header('Location: concerts.php');
