<?php
/**
 * File containing the navbar for the site.
 * User: Erik Wilson
 * Date: 16-Apr-17
 * Time: 19:41
 */

ob_start();
?>
<nav class="navbar navbar-default navbar-fixed-top">
    <!-- Nav bar -->
    <div class="container">
        <!-- Navbar "Home" button -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed"
                    data-toggle="collapse" data-target="#collapsible-navbar"
                    aria-expanded="false">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">Concert
                Tracker</a>
        </div>

        <?php if (isset($_SESSION['user'])) { ?>
        <!-- Other navbar buttons -->
        <div class="collapse navbar-collapse" id="collapsible-navbar">
            <ul class="nav navbar-nav">
                <li><a href="/concerts.php">Concerts</a></li>
                <li><a href="/artists.php">Artists</a></li>
            </ul>
            <!-- Dropdown -->
            <ul class="nav navbar-nav">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"
                       role="button">Data Management<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="/import.php">Import</a></li>
                        <li><a href="/export.php">Export</a></li>
                    </ul>
                </li>
            </ul>
            <!-- Dropdown part 2 -->
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"
                       role="button">
                        <?php 
                        if (isset($_SESSION['username'])) {
                            echo $_SESSION['username'];
                        } else {
                            echo "Account";
                        }
                        ?>
                        <span class="caret"></span></a>
                    <ul class="dropdown-menu">
<!--                        <li><a href="settings.php">Options</a></li>-->
                        <li><a href="logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
            <?php } ?>
        </div>
    </div>
</nav>
<?php
$navbar = ob_get_clean();
