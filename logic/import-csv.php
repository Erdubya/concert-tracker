<?php
/**
 * User: Erik Wilson
 * Date: 17-Apr-17
 * Time: 12:56
 */
//require the config file
require_once "../config.php";
require_once "../_functions.php";
include "../class/Encoding.php";
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
        $csv[] = str_getcsv(Encoding::toUTF8($line));
    }
    if (isset($_POST['headers'])) {
        array_shift($csv); //Drop headers
    }

    // Convert that array into an SQL insert statement
    $sql = [];
    if ($_POST['filetype'] == "artist") {
        foreach ($csv as $row => $line) {
            $name      = $line[0];
            $genre     = $line[1];
            $country   = $line[2];
            $sql[$row] = "(\"$name\", \"$genre\", \"$country\")";
        }
        $sql = "INSERT INTO artist(name, genre, country) VALUES "
               . implode(", ", $sql);
        unset($_POST);
        header('Location: artists.php');
    } elseif ($_POST['filetype'] == "concert") {
        // TODO: does not work, needs foreign key handling
//            foreach ($csv as $row => $line) {
//                $artist    = $line[0];
//                $date      = date_parse($line[1]);
//                $attended  = $line[2];
//                $notes     = $line[3];
//                $sql[$row] = "(\"$artist\", $date, $attended, \"$notes\")";
//            }
//            $sql = "INSERT INTO concert(artist, date, city, notes) VALUES "
//                   . implode(", ", $sql);
//            unset($_POST);
//            header('Location: concerts.php');
    }
    $dbh->exec($sql);
} else {
    echo "NO FILE SELECTED <br>";
}
