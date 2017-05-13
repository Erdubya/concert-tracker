<?php
/**
 * User: Erik Wilson
 * Date: 17-Apr-17
 * Time: 12:56
 */
//require the config file
require_once "config.php";
require_once "_functions.php";
include "class/Encoding.php";
use ForceUTF8\Encoding;

// start the session and connect to DB
session_start();
$dbh = db_connect() or die(ERR_MSG);

if (isset($_FILES['csvfile'])) {
    // TODO: check errors
    $file = $_FILES['csvfile']['tmp_name'];

    // Generate a 2D array from the CSV file
    $csv = [];
    foreach (file($file) as $line) {
        $csv[] = str_getcsv(remove_utf8_bom(Encoding::toUTF8($line)));
    }
    if (isset($_POST['headers'])) {
        array_shift($csv); //Drop headers
    }

    $sql = "";
    if ($_POST['filetype'] == "artist") {
        // Convert that array into an SQL insert statement
        $sql = $dbh->prepare("INSERT INTO artist(name, genre, country) VALUES (:artist, :genre, :country)");
        $sql->bindParam(':artist', $name);
        $sql->bindParam(':genre', $genre);
        $sql->bindParam(':country', $country);

        foreach ($csv as $row => $line) {
            $name    = $line[0];
            $genre   = $line[1];
            $country = $line[2];
            $sql->execute();
        }

        unset($_POST);
        header('Location: artists.php');
    } elseif ($_POST['filetype'] == "concert") {
        // Prepare the statement for insertion
        $sql = $dbh->prepare("INSERT INTO concert(artist_id, date, city, notes, attend) VALUES (:artist, :showdate, :city, :notes, :attend)");
        $sql->bindParam(':artist', $artist);
        $sql->bindParam(':showdate', $date);
        $sql->bindParam(':city', $city);
        $sql->bindParam(':notes', $notes);
        $sql->bindParam(':attend', $attend);

        // Iterate over the uploaded data
        foreach ($csv as $row => $line) {
            // Get the PK of the artist from th DB
            $stmt = $dbh->prepare("SELECT artist_id FROM artist WHERE name = :artistname");
            $stmt->execute(array(':artistname' => $line[0]));
            $artist = $stmt->fetchColumn(0);

            // If the artist exists, insert the concert
            if ($artist != false) {
                $date   = $line[1];
                $city   = $line[2];
                $notes  = $line[3];
                $attend = $line[4];

//                var_dump($artist);
//                var_dump($date);
//                var_dump($city);
//                var_dump($notes);
//                var_dump($attend);

                $sql->execute();
            }
        }

        unset($_POST);
        header('Location: concerts.php');
    }
} else {
    header('Location: import.php');
}
