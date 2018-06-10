<?php
/**
 * User: Erik Wilson
 * Date: 10-May-17
 * Time: 23:39
 */


if (isset($_SESSION['user'])) {
    header("Location: /");
}

$pageTitle = "Login - Concert Tracker";

ob_start();
?>
    <!DOCTYPE html>
    <html lang="en">
    <!-- Include the HTML head -->
    <?php include TEMPLATE_PATH . "/htmlhead.php" ?>
    <body>
    <header>
        <?php
        include TEMPLATE_PATH . "/navbar.php";
        echo $navbar;
        ?>
    </header>

    <main class="container head-foot-spacing">
        <!-- Import from file form -->
        <form class="container panel form-login panel-default"
              action="/api/v1/user/login" method="post">
            <a class="btn btn-sm btn-primary" style="float: right" href="/register">Register Here</a>
            <h2>Login</h2>
            <hr>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" maxlength="50" id="email" class="form-control">
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" class="form-control">
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="remember"> Remember me
                </label>
            </div>
            <hr>
            <button type="submit" class="btn btn-default" name="login">
                Login
            </button>
        </form>
    </main>

    <!-- Simple footer -->
    <?php
    include TEMPLATE_PATH . '/footer.php';
    echo $footer;
    ?>

    </body>
    </html>
<?php
ob_end_flush();
