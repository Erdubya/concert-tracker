<?php
/**
 * User: Erik Wilson
 * Date: 16-Apr-17
 * Time: 00:49
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
    header("'Location: login.php'");
} else {
    $userid = $_SESSION['user'];
}

$pageTitle = "Artists - Concert Tracker";

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
        <!-- Set up tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li class="active" role="presentation"><a href="#panel1" role="tab"
                                                      data-toggle="tab">Artists</a>
            </li>
            <li role="presentation"><a href="#panel2" role="tab"
                                       data-toggle="tab">Add</a></li>
        </ul>
        <div class="tab-content">
            <!-- Tab 1 -->
            <div role="tabpanel" class="tab-pane active" id="panel1">
                <!-- ARTIST LIST -->
                <div class="table-responsive">
                    <table class="table table-condensed">
                        <thead>
                        <tr>
                            <th>Artist</th>
                            <th>Genre</th>
                            <th>Country</th>
                            <th class="col-xs-1">Upcoming</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($dbh->query("SELECT * FROM artist ORDER BY name ASC ") as $result) {
                            echo "<tr>";
                            // Set click action for this cell to open the appropriate modal
                            echo "<td data-toggle='modal' data-target='#artist-modal'
                         data-artist='" . $result['name'] . "' 
                         data-genre='" . $result['genre'] . "' 
                         data-country='" . $result['country'] . "'
                         data-id='" . $result['artist_id'] . "'>"
                                 . $result['name']
                                 . "</td>";
                            echo "<td>" . $result['genre'] . "</td>";
                            echo "<td>" . $result['country'] . "</td>";
                            echo "<td><a class='btn btn-xs btn-primary center-block' href='http://www.google.com/#q=" . urlencode($result['name']) . "+tour' target='_blank'>Search!</a>";
                            echo "</tr>";
                        }
                        ?>
                        </tbody>
                    </table>
                </div>

                <!-- EDIT MODAL -->
                <div class="modal fade" id="artist-modal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close"
                                        data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <h4 class="modal-title" id="myModalLabel">Edit
                                    Artist</h4>
                            </div>
                            <div class="modal-body">
                                <form id="edit-form" action="edit-artist.php"
                                      method="post">
                                    <input hidden title="id" type="text"
                                           id="artist-id" name="id">
                                    <div class="form-group">
                                        <label for="artist-edit"
                                               class="control-label">Artist</label>
                                        <input type="text" id="artist-edit"
                                               name="artist"
                                               class="form-control"
                                               maxlength="50" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="genre-edit"
                                               class="control-label">Genre</label>
                                        <input type="text" id="genre-edit"
                                               name="genre" class="form-control"
                                               maxlength="50">
                                    </div>
                                    <div class="form-group">
                                        <label for="country-edit"
                                               class="control-label">Country</label>
                                        <input type="text" id="country-edit"
                                               name="country"
                                               class="form-control"
                                               maxlength="50">
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default"
                                        data-dismiss="modal">Close
                                </button>
                                <button type="submit" class="btn btn-primary"
                                        form="edit-form" name="submit">Save
                                    Changes
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab 2 -->
            <div role="tabpanel" class="tab-pane" id="panel2">
                <!-- ADD FORM -->
                <form class="container" action="add-artist.php" method="post">
                    <h2>Add Artist</h2>
                    <hr>
                    <div class="form-group">
                        <label for="artist_name">Artist</label>
                        <input class="form-control" type="text"
                               name="artist_name"
                               id="artist_name" maxlength="50" required>
                    </div>
                    <div class="form-group">
                        <label for="genre">Genre</label>
                        <input class="form-control" type="text" name="genre"
                               id="genre" maxlength="50">
                    </div>
                    <div class="form-group">
                        <label for="country">Country</label>
                        <input class="form-control" type="text" name="country"
                               id="country" maxlength="50">
                    </div>
                    <hr>
                    <button type="submit" class="btn btn-default" name="submit">
                        Submit
                    </button>
                </form>
            </div>
        </div>
    </main>

    <!-- Simple footer -->
    <?php
    include 'footer.php';
    echo $footer;
    ?>

    <script>
        // Control navbar active highlighting
        $(document).ready(function () {
            // get current URL path and assign 'active' class to navbar
            var pathname = new URL(window.location.href).pathname.split('/').pop();
            if (pathname !== "") {
                $('.nav > li > a[href="' + pathname + '"]').parent().addClass('active');
            }
        });

        // Set dynamic data in the edit modal
        $('#artist-modal').on('show.bs.modal', function (event) {
            var link = $(event.relatedTarget); // Item that triggered the modal
            var artist = link.data('artist'); // Extract info from data-* attributes
            var genre = link.data('genre');
            var country = link.data('country');
            var id = link.data('id');

            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            var modal = $(this);
            modal.find('.modal-body #artist-edit').val(artist);
            modal.find('.modal-body #genre-edit').val(genre);
            modal.find('.modal-body #country-edit').val(country);
            modal.find('.modal-body #artist-id').val(id);
        });
    </script>
    </body>
    </html>
<?php
ob_end_flush();
