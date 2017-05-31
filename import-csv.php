<?php
/**
 * User: Erik Wilson
 * Date: 17-Apr-17
 * Time: 12:56
 */
//require the config file
require_once "config.php";
require_once "_functions.php";
include "lib/Encoding.php";
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
        $sql = $dbh->prepare("INSERT INTO artist(user_id, name, genre, country) VALUES (:userid, :artist, :genre, :country)");
        $sql->bindParam(':userid', $user);
        $sql->bindParam(':artist', $name);
        $sql->bindParam(':genre', $genre);
        $sql->bindParam(':country', $country);

        $user = $_SESSION['user'];
        foreach ($csv as $row => $line) {
            $name    = $line[0];
            $genre   = $line[1];
            $country = $line[2];
            
            // run only if artist does not exist
            $check = $dbh->prepare("SELECT artist_id FROM artist WHERE name=?");
            $check->execute($name);
            $result = $check->fetch();
            if ($result = false) {
                $sql->execute();
            }
        }

        unset($_POST);
        header('Location: artists.php');
    } elseif ($_POST['filetype'] == "concert") {
        // Prepare the statement for insertion
        $sql_str = "INSERT INTO concert(artist_id, date, city, notes, attend) VALUES (:artist, :showdate, :city, :notes, :attend)";
        $sql = $dbh->prepare($sql_str);
        $sql->bindParam(':artist', $artist);
        $sql->bindParam(':showdate', $date);
        $sql->bindParam(':city', $city);
        $sql->bindParam(':notes', $notes);
        $sql->bindParam(':attend', $attend);

        // Iterate over the uploaded data
        foreach ($csv as $row => $line) {
            // Get the PK of the artist from th DB
            $stmt = $dbh->prepare("SELECT artist_id FROM artist WHERE name = :artistname AND user_id = :userid");
            $stmt->execute(array(':artistname' => $line[0], ':userid' => $_SESSION['user']));
            $artist = $stmt->fetchColumn(0);

            // If the artist exists, insert the concert
            if ($artist != false) {
                $date   = $line[1];
                $city   = $line[2];
                $notes  = $line[3];
                $attend = $line[4];
                
                // fix boolean return from postgres
                if (is_string($attend)) {
                    $attend = (int)$attend;
                }

//                $array = array(
//                    "artist" => $artist, 
//                    "showdate" => $date,
//                    "city" => $city,
//                    "notes" => $notes,
//                    "attend" => $attend);
//                var_dump($array);
//                echo get_prep_stmt($sql_str, $array);

                $sql->execute();
            }
        }

        unset($_POST);
        header('Location: concerts.php');
    }
} else {
    header('Location: import.php');
}
