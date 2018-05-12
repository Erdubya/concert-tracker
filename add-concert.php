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

if (isset($_POST['add'])) {
    $stmt = $dbh->prepare("INSERT INTO concert(date, city, venue, attend, notes) VALUES (:showdate, :city, :venue, :attend, :notes)");
    $stmt->bindParam(':showdate', $date);
    $stmt->bindParam(':city', $city);
    $stmt->bindParam(':venue', $venue);
    $stmt->bindParam(':attend', $attend);
    $stmt->bindParam(':notes', $notes);

    $date   = $_POST['date'];
    $city   = $_POST['city'];
    $venue   = $_POST['venue'];
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
