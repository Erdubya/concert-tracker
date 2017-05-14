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
        $stmt = $dbh->prepare("SELECT name, genre, country FROM artist WHERE user_id = :userid ORDER BY name ASC ");
        $stmt->bindParam(":userid", $_SESSION['user']);
        $stmt->execute();
        
        $csv_array = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $output    = fopen("php://output", "w");
        
        foreach ($csv_array as $line) {
            fputcsv($output, $line);
        }
    } elseif ($_POST['data'] == 'concert') {
        $sql = "SELECT a.name, c.date, c.city, c.notes, c.attend 
                FROM concert AS c, artist AS a 
                WHERE a.artist_id = c.artist_id
                  AND a.user_id = :userid
                ORDER BY a.name ASC";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(":userid", $_SESSION['user']);
        $stmt->execute();
        
        $csv_array = $stmt->fetchAll(PDO::FETCH_ASSOC);
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

