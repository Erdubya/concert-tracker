<?php
/**
 * User: Erik Wilson
 * Date: 13-Apr-17
 * Time: 21:23
 */

/** Function to connect to a database
 * Uses settings provided in _configuration.php
 * @return null|PDO
 */
function db_connect()
{
    try {
        $dbh = new PDO(HANDLER . ":host=" . HOSTNAME . ";dbname=" . DATABASE,
            USERNAME, PASSWORD);
    } catch (PDOException $e) {
        $dbh = null;
    }

    return $dbh;
}

/**
 * @param $dbh     PDO The connection to build the database in
 * @param $handler string The DB handler to create the DB for
 *
 * @return bool The success state of the database creation
 */
function build_db($dbh, $handler)
{
    if ($handler == "mysql") {
        $artist_table  = "CREATE TABLE IF NOT EXISTS artist(
						artist_id SERIAL PRIMARY KEY ,
						name VARCHAR(50) UNIQUE NOT NULL ,
						genre VARCHAR(50) NOT NULL , 
						country VARCHAR(50) NOT NULL
						)";
        $concert_table = "CREATE TABLE IF NOT EXISTS concert(
						concert_id SERIAL PRIMARY KEY ,
						artist BIGINT UNSIGNED NOT NULL , 
						date DATE NOT NULL , 
						city VARCHAR(30) NOT NULL , 
						attend BOOLEAN NOT NULL DEFAULT FALSE,
						notes VARCHAR(500), 
						FOREIGN KEY (artist) REFERENCES artist(artist_id)
						)";
    } else {
        $artist_table = NULL;
        $concert_table = NULL;
    }
    try {
        $dbh->exec($artist_table);
        $dbh->exec($concert_table);

        return true;
    } catch (PDOException $e) {
        return false;
    }
}

/**
 * checks if the program is installed, and ends the script if it is not
 */
function check_install()
{
    if ( !file_exists('config.php')) {
        die("Please run the <a href='install.php'>install script</a> set up Concert Tracker.");
    }
}
