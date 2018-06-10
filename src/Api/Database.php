<?php
/**
 * User: Erik Wilson
 * Date: 17-May-18
 * Time: 18:46
 */

namespace Vir\Api;


class Database
{


    public static function add_new_concert(\PDO $dbh, array $params)
    {

        $sql = Sql::add_new_concert();




        return;
    }

    public static function get_next_concert(\PDO $dbh, array $params)
    {
        $sql = Sql::get_next_concert();

        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(":user", $params['user']);
        $stmt->bindParam(":date", $params['date']);

        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public static function get_user_login_info(\PDO $dbh, array $params)
    {
        $sql = Sql::get_user_login_info();

        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(":email", $params['email']);

        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function create_new_auth_token(\PDO $dbh, array $params)
    {
        $sql = Sql::create_new_auth_token();

        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(":selector", $params['selector']);
        $stmt->bindParam(":token", $params['token']);
        $stmt->bindParam(":userid", $params['user']);
        $stmt->bindParam(":expires", $params['expires']);

        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
