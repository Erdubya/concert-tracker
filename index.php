<?php
/**
 * User: Erik Wilson
 * Date: 13-Apr-17
 * Time: 21:23
 */
require_once '_functions.php';
check_install();

//require the config file
require_once "config.php";

// start the session and connect to DB
session_start();
$dbh = db_connect() or die(ERR_MSG);

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
} else {
    $userid = $_SESSION['user'];
}

$pageTitle = "Concert Tracker";

ob_start();
?>
    <!DOCTYPE html>
    <html lang="en">
    <?php
    // Include the HTML head
    include "htmlhead.php" 
    ?>
    <body>
    <header>
        <?php
        include "navbar.php";
        echo $navbar;
        ?>
    </header>

    <main class="container head-foot-spacing">
        <h3>Next show:</h3>

        <div class="jumbotron">
            <?php
            $date = date("Y-m-d");
            $sql = "SELECT name, city, date, notes, genre, country 
                    FROM concert, artist 
                    WHERE date >= :date 
                      AND attend = TRUE 
                      AND concert.artist_id = artist.artist_id 
                      AND artist.user_id = :user
                    ORDER BY date LIMIT 1";
            
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(":date", $date);
            $stmt->bindParam(":user", $_SESSION['user']);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result != false) {
                ?>
                <h1><?php echo $result['name'] ?>
                    <small> <?php echo date("D, d M Y",
                            strtotime($result['date'])) ?></small>
                </h1>
                <h3>Concert Info:</h3>
                <ul>
                    <?php 
                    if (!is_null($result['city']) and $result['city'] !== '') {
                        echo '<li>' . $result['city'] . '</li>';
                    } 
                    if (!is_null($result['notes']) and $result['notes'] !== '') {
                        echo '<li>' . $result['notes'] . '</li>';
                    }
                    ?>
                </ul>
                <h3>Artist Info:</h3>
                <ul>
                    <?php
                    if (!is_null($result['country']) and $result['country'] !== '') {
                        echo '<li>' . $result['country'] . '</li>';
                    }
                    if (!is_null($result['genre']) and $result['genre'] !== '') {
                        echo '<li>' . $result['genre'] . '</li>';
                    }
                    ?>
                </ul>
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
    include 'footer.php';
    echo $footer;
    ?>
    
    <script>
        // Handle navbar dynamic highlighting
        $(document).ready(function () {
            // get current URL path and assign 'active' class to navbar
            var pathname = new URL(window.location.href).pathname.split('/').pop();
            if (pathname !== "") {
                $('.nav > li > a[href="' + pathname + '"]').parent().addClass('active');
            }
        })
    </script>
    </body>
    </html>
<?php
ob_end_flush();
