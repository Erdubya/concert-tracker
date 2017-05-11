<?php
/**
 * User: Erik Wilson
 * Date: 17-Apr-17
 * Time: 01:09
 */
require_once "config.php";
require_once "_functions.php";
session_start();
$dbh = db_connect() or die(ERR_MSG);

if (isset($_POST['export'])) {
    ob_start();
    
    header("Content-Type: text/csv");
    header("Content-Disposition: attachment; filename=" . $_POST['data'] . "s.csv");
    
    if (isset($_POST['bom'])) {
        echo "\xEF\xBB\xBF"; // UTF-8 BOM for EXCEL use
    }

    if ($_POST['data'] == 'artist') {
        $csv_array = $dbh->query("SELECT name, genre, country FROM artist ORDER BY name ASC ",
            PDO::FETCH_ASSOC);
        $output    = fopen("php://output", "w");
        foreach ($csv_array as $line) {
            fputcsv($output, $line);
        }
    } elseif ($_POST['data'] == 'concert') {
        $csv_array = $dbh->query("SELECT a.name, c.date, c.city, c.notes, c.attend FROM concert AS c, artist AS a WHERE a.artist_id = c.artist ORDER BY a.name ASC ",
            PDO::FETCH_ASSOC);
        $output    = fopen("php://output", "w");
        foreach ($csv_array as $line) {
            fputcsv($output, $line);
        }
    }

    ob_end_flush();
} else {
    unset($_POST);
    header('Location: export.php');
}

