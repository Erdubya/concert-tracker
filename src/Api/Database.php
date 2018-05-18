<?php
/**
 * User: Erik Wilson
 * Date: 17-May-18
 * Time: 18:46
 */

namespace Vir\Api;


class Database
{


    public static function add_new_concert()
    {

        $sql = Sql::add_new_concert();


        return;
    }

    public static function login_user()
    {
        $sql = Sql::login_user();

    }
}
