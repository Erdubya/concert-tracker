<?php
/**
 * User: Erik Wilson
 * Date: 17-May-18
 * Time: 16:50
 */

namespace Vir\Cls;

class Database
{
    /**
     * @param $db_object object
     */
    public static function create_pdo($db_object)
    {
        $conn_str = $db_object->handler;
        $conn_str .= ":host=" . $db_object->hostname;
        $conn_str .= ";port=" . $db_object->port;
        $conn_str .= ";dbname=" . $db_object->db_name;
        try {
            $dbh = new \PDO($conn_str, $db_object->db_user, $db_object->db_pass);
        } catch (\PDOException $e) {
            $dbh = null;
        }

        return $dbh;
    }

    /**
     * @param $dbh \PDO
     *
     * @return bool
     */
    public static function build_db($dbh)
    {
        unset($tables);
        $tables['user']            =
            "CREATE TABLE IF NOT EXISTS users(
        user_id SERIAL,
        email VARCHAR(30) UNIQUE NOT NULL ,
        passwd VARCHAR(255) NOT NULL ,
        name VARCHAR(50) NOT NULL ,
        PRIMARY KEY (user_id))";
        $tables['artist']          =
            "CREATE TABLE IF NOT EXISTS artist(
        artist_id SERIAL ,
        user_id INT NOT NULL ,
        name VARCHAR(50) NOT NULL ,
        genre VARCHAR(50) NULL , 
        country VARCHAR(50) NULL ,
        hidden BOOLEAN NOT NULL DEFAULT FALSE ,
        PRIMARY KEY (artist_id),
        FOREIGN KEY (user_id) REFERENCES users(user_id),
        UNIQUE (user_id, name))";
        $tables['concert']         =
            "CREATE TABLE IF NOT EXISTS concert(
        concert_id SERIAL ,
        date DATE NOT NULL , 
        city VARCHAR(30) NOT NULL , 
        venue VARCHAR(30) NULL ,
        attend BOOLEAN NOT NULL DEFAULT FALSE,
        notes VARCHAR(500) ,
        PRIMARY KEY (concert_id));";
        $tables['concert_artists'] =
            "CREATE TABLE IF NOT EXISTS concert_artists(
        id SERIAL ,
        artist_id INT NOT NULL ,
        concert_id INT NOT NULL , 
        is_primary BOOLEAN NOT NULL DEFAULT FALSE,
        PRIMARY KEY (id) ,
        FOREIGN KEY (artist_id) REFERENCES artist(artist_id) ,
        FOREIGN KEY (concert_id) REFERENCES concert(concert_id))";
        $tables['token']           =
            "CREATE TABLE IF NOT EXISTS auth_token(
        token_id SERIAL ,
        selector CHAR(12) UNIQUE NOT NULL ,
        token CHAR(64) NOT NULL ,
        user_id INT NOT NULL ,
        expires TIMESTAMP NOT NULL ,
        PRIMARY KEY (token_id) ,
        FOREIGN KEY (user_id) REFERENCES users(user_id))";
        // attempt to execute the queries
        try {
            $dbh->exec($tables['user']);
            $dbh->exec($tables['artist']);
            $dbh->exec($tables['concert']);
            $dbh->exec($tables['concert_artists']);
            $dbh->exec($tables['token']);

            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }
}
