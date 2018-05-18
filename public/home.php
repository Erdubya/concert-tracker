<?php
/**
 * User: Erik Wilson
 * Date: 13-Apr-17
 * Time: 21:23
 */
require_once BASE_PATH . '/vendor/autoload.php';
if (!$config = config_loader()) {
    request_install();
}

// start the session and connect to DB
session_start();
$dbh = \Vir\Classes\Database::create_pdo($config->database);

cookie_loader($dbh);

$userid = check_login();

$pageTitle = "Concert Tracker";

ob_start();
?>
    <!DOCTYPE html>
    <html lang="en">
    <?php
    // Include the HTML head
    include TEMPLATE_PATH . "/htmlhead.php"
    ?>
    <body>
    <header>
        <?php
        include TEMPLATE_PATH . "/navbar.php";
        ?>
    </header>

    <main class="container head-foot-spacing">
        <h3>Next show:</h3>

        <div class="jumbotron">
            <?php
            $date = date("Y-m-d");
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

            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(":date", $date);
            $stmt->bindParam(":user", $_SESSION['user']);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            // get artist strings
            $primaries = "";
            $openers   = "";
            if ($result != false) {
                foreach (json_decode($result['o_artists']) as $artist) {
                    if ($openers == "") {
                        $openers .= $artist;
                    } else {
                        $openers .= ", " . $artist;
                    }
                }
                foreach (json_decode($result['p_artists']) as $artist) {
                    if ($primaries == "") {
                        $primaries .= $artist;
                    } else {
                        $primaries .= ", " . $artist;
                    }
                }
                ?>
                <h3><span class="avoidwrap"><?php echo date("D, d M Y", strtotime($result['date'])) ?></span></h3>
                <h1><?php echo $primaries ?></h1>
                <h2><?php echo $openers ?></h2>
                <h3>Concert Info:</h3>
                <ul>
                    <?php
                    if (!is_null($result['city']) and $result['city'] !== '') {
                        echo '<li>' . $result['city'] . '</li>';
                    }
                    if (!is_null($result['venue']) and $result['venue'] !== '') {
                        echo '<li>' . $result['venue'] . '</li>';
                    }
                    if (!is_null($result['notes']) and $result['notes'] !== '') {
                        echo '<li>' . $result['notes'] . '</li>';
                    }
                    ?>
                </ul>

                <?php
                $stmt = $dbh->prepare("SELECT country, genre FROM artist WHERE name = :artist AND user_id = :user");
                $stmt->bindParam(':artist', $artist);
                $stmt->bindParam(':user', $_SESSION['user']);
                foreach (json_decode($result['p_artists']) as $artist) {
                    echo "<h3>" . $artist . "</h3>";
                    echo "<ul>";
                    $stmt->execute();
                    $result = $stmt->fetch();
                    if (!is_null($result['country']) and $result['country'] !== '') {
                        echo '<li>' . $result['country'] . '</li>';
                    }
                    if (!is_null($result['genre']) and $result['genre'] !== '') {
                        echo '<li>' . $result['genre'] . '</li>';
                    }
                    echo "</ul>";
                }?>
                <?php
            } else {
                echo "<h2>Not attending any concerts</h2>";
                echo "<p>Check the concerts page for upcoming shows!</p>";
            }
            ?>
        </div>
        <!-- Upcoming? -->
    </main>

    <!-- Simple footer -->
    <?php
    include TEMPLATE_PATH . '/footer.php';
    echo $footer;
    ?>

    <script>
        // Handle navbar dynamic highlighting
        $(document).ready(function () {
            // get current URL path and assign 'active' class to navbar
            let pathname = new URL(window.location.href).pathname.split('/').pop();
            if (pathname !== "") {
                $('.nav > li > a[href="' + pathname + '"]').parent().addClass('active');
            }
        })
    </script>
    </body>
    </html>
<?php
ob_end_flush();
