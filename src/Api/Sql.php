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

    public static function get_next_concert()
    {
        $sql = "SELECT
                  c.concert_id,
                  c.date,
                  c.city,
                  c.venue,
                  c.attend,
                  c.notes,
                  array_to_json(
                    ARRAY(
                      select 
                        name 
                      from 
                        artist
                        join concert_artists a 
                          on artist.artist_id = a.artist_id
                      where 
                        is_primary = true 
                        and c.concert_id = a.concert_id
                    )
                  ) p_artists,
                  array_to_json(
                    ARRAY(
                      select 
                        name 
                      from 
                        artist
                        join concert_artists a 
                          on artist.artist_id = a.artist_id
                      where 
                        is_primary = false 
                        and c.concert_id = a.concert_id
                    )
                  ) o_artists
                FROM
                  concert c,
                  artist a
                WHERE
                  date >= :date
                  AND a.user_id = :user
                  AND attend = TRUE 
                GROUP BY
                  c.concert_id
                ORDER BY
                  c.date DESC;";

        return $sql;
    }

    public static function get_user_login_info()
    {
        $sql = "SELECT
                  * /*
                  user_id,
                  passwd,
                  name
                  */ 
                FROM 
                  users
                WHERE 
                  email = :email;";

        return $sql;
    }

    public static function create_new_auth_token()
    {
        $sql = "INSERT INTO
                  auth_token(
                    selector,
                    token, 
                    user_id, 
                    expires
                  ) VALUES (
                    :selector, 
                    :token, 
                    :userid, 
                    :expires
                  );";

        return $sql;
    }

}
