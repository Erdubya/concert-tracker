<?php
/**
 * User: Erik Wilson
 * Date: 17-May-18
 * Time: 18:39
 */

namespace Vir\Api;


class Sql
{
    public static function add_new_concert()
    {
        $sql = "INSERT INTO 
                  concert(
                    date,
                    city,
                    venue,
                    attend,
                    notes
                  ) 
                VALUES (
                  :showdate,
                  :city,
                  :venue,
                  :attend,
                  :notes
                );";

        return $sql;
    }

    public static function login_user()
    {
        $sql = "SELECT
                  user_id,
                  passwd,
                  name 
                FROM 
                  users
                WHERE
                  email = :email;";

        return $sql;
    }

}
