<?php
/**
 * User: Erik Wilson
 * Date: 25-May-17
 * Time: 12:37
 */
require_once '_functions.php';
check_install();

//require the config file
require_once "config.php";

// start the session and connect to DB
session_start();
$dbh = db_connect() or die(ERR_MSG);

// redirect if not logged in
if (!isset($_SESSION['user'])) {
    $userid = null;
    header("Location: login.php");
} else {
    $userid = $_SESSION['user'];
}

$pageTitle = "Profile - Concert Tracker";

$sql  = "SELECT * FROM users WHERE user_id=?";
$stmt = $dbh->prepare($sql);
$stmt->execute(array($userid));

$userdata = $stmt->fetch(PDO::FETCH_ASSOC);

ob_start();
?>
    <!DOCTYPE html>
    <html lang="en">
    <!-- Include the HTML head -->
    <?php include "htmlhead.php" ?>
    <body>
    <header>
        <?php
        include "navbar.php";
        echo $navbar;
        ?>
    </header>
    <main class="container head-foot-spacing">
        <div>
            <form class="form-login container panel panel-default" 
                  method="post" action="user-update.php">
                <h2>Update User Info</h2>
                <small>Change all or part of your info</small>
                <hr>
                <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" name="name" id="name"
                       title="name" value="<?php echo $userdata['name'] ?>"
                       maxlength="50" required>
                </div>
                <hr>
                <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" name="email" id="email"
                       title="email" value="<?php echo $userdata['email'] ?>"
                       maxlength="30" required>
                </div>
                <hr>
                <div class="form-group">
                    <label for="newpass">New Password</label>
                    <input type="password" class="form-control" name="newpass"
                           id="newpass" minlength="8">
                </div>
                <div class="form-group" id="confirm-div">
                    <label for="newpass-conf">Confirm New Password</label>
                    <input type="password" class="form-control"
                           name="newpass-conf"
                           id="newpass-conf" minlength="8">
                    <span id="helpblock-match" class="help-block hidden">
                        Passwords must match
                    </span>
                </div>
                <hr>
                <div class="form-group">
                    <label for="curpass">Current Password</label>
                    <input type="password" class="form-control" name="curpass"
                           id="curpass" maxlength="64">
                </div>
                <button class="btn btn-primary" type="submit" name="update">
                    Update
                </button>
            </form>

        </div>

    </main>

    <!-- Simple footer -->
    <?php
    include 'footer.php';
    echo $footer;
    ?>
    
    <script>
        function checkPasswordMatch() {
            // set variables
            var password        = $("#newpass").val();
            var confirmPassword = $("#newpass-conf").val();
            var div             = $("#confirm-div");
            var matchHelp       = $("#helpblock-match");

            // check password confirmation
            if (password === "" && confirmPassword === "") {
                div.removeClass("has-success");
                div.removeClass("has-error");
                matchHelp.addClass("hidden");
            } else if (password !== confirmPassword) {
                div.removeClass("has-success");
                div.addClass("has-error");
                matchHelp.removeClass("hidden");
            } else {
                div.removeClass("has-error");
                div.addClass("has-success");
                matchHelp.addClass("hidden");
            }
        }

        $(document).ready(function () {
            $("#newpass, #newpass-conf").keyup(checkPasswordMatch);
        });
    </script>

    </body>
    </html>

<?php
ob_end_flush();
