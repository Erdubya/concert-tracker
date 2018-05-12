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


var_dump($_POST);
//die();

if (isset($_POST['update'])) {
    $stmt = $dbh->prepare("UPDATE concert SET date=:showdate, city=:city, attend=:attend, notes=:notes WHERE concert_id=:id");
//    $stmt->bindParam(':artist', $artist);
    $stmt->bindParam(':showdate', $date);
    $stmt->bindParam(':city', $city);
    $stmt->bindParam(':attend', $attend);
    $stmt->bindParam(':notes', $notes);
    $stmt->bindParam(':id', $id);

//    $artist = (int)$_POST['artist'];
    $date   = $_POST['date'];
    $city   = $_POST['city'];
    $notes  = $_POST['notes'];
    $id     = (int)$_POST['id'];
    if (isset($_POST['attend'])) {
        $attend = 1;
    } else {
        $attend = 0;
    }

    $stmt->execute();

    delete_old_artists($dbh, $id);
    add_new_artists($dbh, $id, $_POST['p_artist'], $_POST['o_artist']);
} elseif (isset($_POST['delete'])) {
    $stmt = $dbh->prepare("DELETE FROM concert WHERE concert_id=:id");

    $stmt->bindParam(':id', $id);

    $id = (int)$_POST['id'];

    $stmt->execute();
}

/**
 * @param $dbh        PDO database handler
 * @param $concert_id int id of the concert
 */
function delete_old_artists($dbh, $concert_id)
{
    $stmt = $dbh->prepare("DELETE FROM concert_artists WHERE concert_id=:cid");
    $stmt->bindParam(':cid', $concert_id);

    $stmt->execute();
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
//die();
header('Location: concerts.php');
