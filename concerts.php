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
    header("Location: login.php");
} else {
    $userid = $_SESSION['user'];
}

// set the page title
$pageTitle     = "Concerts - Concert Tracker";

// include script for checkbox formatting
$extraIncludes = array(
    "<script src='js/bootstrap-checkbox.js' defer></script>"
);

// get list of artist names
$stmt = $dbh->prepare("SELECT artist_id, name FROM artist WHERE user_id=:userid ORDER BY " . artist_sort_sql() . " ASC ");
$stmt->bindParam(":userid", $userid);
$stmt->execute();
$artist_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
                                                      data-toggle="tab">Concerts</a>
            </li>
            <li role="presentation"><a href="#panel2" role="tab"
                                       data-toggle="tab">Add</a></li>
        </ul>
        <div class="tab-content">
            <!-- Tab 1 -->
            <div role="tabpanel" class="tab-pane active" id="panel1">
                <!-- Concert List -->
                <div class="table-responsive">
                    <table class="table table-condensed">
                        <thead>
                        <tr>
                            <th class="col-xs-2">Date</th>
                            <th>Artist</th>
                            <th>City</th>
                            <th class="col-xs-1 text-center">Attend</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        
                        // Get list of concerts
                        $sql = "SELECT c.concert_id, a.name, c.artist_id, c.date, c.city, c.notes, c.attend 
                                FROM concert AS c, artist AS a 
                                WHERE a.artist_id = c.artist_id
                                  AND a.user_id = :userid
                                ORDER BY c.date DESC";
                        $stmt = $dbh->prepare($sql);
                        $stmt->bindParam(":userid", $userid);
                        $stmt->execute();
                        
                        // display concerts
                        foreach ($stmt->fetchAll() as $key => $result) {
                            $current_date = date("Y-m-d");
                            
                            // Check if the show was in the past and highlight the row accordingly
                            if ($result['date'] < $current_date && !$result['attend']) {
                                echo "<tr class='warning'>";
                            } elseif ($result['date'] < $current_date) {
                                echo "<tr class='active'>";
                            } else {
                                echo "<tr>";
                            }
                            
                            //date column, with data for edit display
                            echo "<td data-toggle='modal' 
                                      data-target='#concert-modal' 
                                      data-id='"     . $result['concert_id'] . "' 
                                      data-date='"   . $result['date'] . "' 
                                      data-city='"   . $result['city'] . "' 
                                      data-notes='"  . $result['notes'] . "' 
                                      data-attend='" . $result['attend'] . "' 
                                      data-artist='" . $result['artist_id'] . "'>"
                                 . $result['date']
                                 . "</td>";
                            
                            // artist and city
                            echo "<td>" . $result['name'] . "</td>";
                            echo "<td>" . $result['city'] . "</td>";
                            
                            // attendance
                            echo "<td class='text-center'>";
                            // Set symbol for attendance bool
                            if ($result['attend']) {
                                echo "<span class='glyphicon glyphicon-ok'>";
                            } else {
                                echo "<span class='glyphicon glyphicon-remove'>";
                            }
                            echo "</td>";
                            
                            // end row
                            echo "</tr>";
                        }
                        ?>
                        </tbody>
                    </table>
                </div>

                <!-- EDIT MODAL -->
                <div class="modal fade" id="concert-modal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close"
                                        data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <h4 class="modal-title" id="myModalLabel">Edit
                                    Concert</h4>
                            </div>
                            <div class="modal-body">
                                <form id="edit-form" action="edit-concert.php"
                                      method="post">
                                    <input hidden title="id" type="text"
                                           id="concert-id" name="id">
                                    <div class="form-group">
                                        <label for="date-edit"
                                               class="control-label">Date</label>
                                        <input type="date" id="date-edit"
                                               name="date" class="form-control"
                                               maxlength="50">
                                    </div>
                                    <div class="form-group">
                                        <label for="artist-edit"
                                               class="control-label">Artist</label>
                                        <select id="artist-edit" name="artist"
                                                class="form-control">
                                            <?php
                                            // Display list of available artists
                                            foreach ($artist_list as $result) {
                                                echo "<option value='" . $result['artist_id'] . "'>" . $result['name'] . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="city-edit"
                                               class="control-label">Country</label>
                                        <input type="text" id="city-edit"
                                               name="city" class="form-control"
                                               maxlength="50">
                                    </div>
                                    <div class="form-group">
                                        <label for="notes-edit"
                                               class="control-label">Notes</label>
                                        <textarea id="notes-edit" name="notes"
                                                  class="form-control"
                                                  maxlength="500"
                                                  rows="10"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="attend-edit">Attending</label><br>
                                        <input title="attend" type="checkbox"
                                               id="attend-edit" name="attend"
                                               data-reverse
                                               data-group-cls="btn-group-sm">
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary"
                                        form="edit-form" name="update">Save
                                    Changes
                                </button>
                                <button type="button" class="btn btn-default"
                                        data-dismiss="modal">Close
                                </button>
                                <button type="submit" class="btn btn-danger"
                                        form="edit-form" name="delete">Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab 2 -->
            <div role="tabpanel" class="tab-pane" id="panel2">
                <!-- Add form -->
                <form class="container" action="add-concert.php"
                      method="post">
                    <h2>Add Concert</h2>
                    <hr>
                    <div class="form-group">
                        <label for="artist_id">Artist</label>
                        <select id="artist_id" class="form-control"
                                name="artist_id"
                                required>
                            <option readonly selected disabled>Select an Artist
                            </option>
                            <?php
                            // Display list of available artists
                            foreach ($artist_list as $result) {
                                echo "<option value='" . $result['artist_id'] . "'>" . $result['name'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="date">Date</label>
                        <input class="form-control" type="date" name="date"
                               id="date" required>
                    </div>
                    <div class="form-group">
                        <label for="city">City</label>
                        <input class="form-control" type="text" name="city"
                               id="city" maxlength="30" required>
                    </div>
                    <div class="form-group">
                        <label for="attend-add" class="control-label">I'm
                            going!</label><br>
                        <input id="attend-add" type="checkbox" name="attend"
                               class="checkbox-inline" data-reverse
                               data-group-cls="btn-group-sm">
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
        // Set navbar active highlighting
        $(document).ready(function () {
            // get current URL path and assign 'active' class to navbar
            var pathname = new URL(window.location.href).pathname.split('/').pop();
            if (pathname !== "") {
                $('.nav > li > a[href="' + pathname + '"]').parent().addClass('active');
            }

            $(':checkbox').checkboxpicker();
        });

        // Set dynamic data in the edit modal
        $('#concert-modal').on('show.bs.modal', function (event) {
            var link = $(event.relatedTarget); // Item that triggered the modal

            var date = link.data('date'); // Extract info from data-* attributes
            var artist = link.data('artist');
            var city = link.data('city');
            var notes = link.data('notes');
            var attend = link.data('attend');
            var id = link.data('id');

            console.log(date);
            console.log(artist);
            console.log(city);
            console.log(notes);
            console.log(attend);
            console.log(id);

            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            var modal = $(this);
            modal.find('.modal-body #date-edit').val(date);
            modal.find('.modal-body #artist-edit').val(artist);
            modal.find('.modal-body #city-edit').val(city);
            modal.find('.modal-body #notes-edit').val(notes);
            if (attend === 1) {
                modal.find('.modal-body #attend-edit').prop('checked', true);
            }
            modal.find('.modal-body #concert-id').val(id);
        });

        //    function change_attend() {
        //        var attend = $('#attend-edit');
        //        var attbtn = $('#attend-btn');
        //        var attspn = attbtn.find('span');
        //        
        //        if (attend.attr('checked')) {
        //            attbtn.addClass('btn-danger');
        //            attbtn.removeClass('btn-success');
        //            attspn.addClass('glyphicon-remove');
        //            attspn.removeClass('glyphicon-ok');
        //            attend.removeAttr('checked');
        //        } else {
        //            attbtn.removeClass('btn-danger');
        //            attbtn.addClass('btn-success');
        //            attspn.removeClass('glyphicon-remove');
        //            attspn.addClass('glyphicon-ok');
        //            attend.prop('checked', true);
        //        }
        //    }

        $('textarea').on('keyup', function () {
            $(this).val($(this).val().replace(/[\r\n\v]+/g, ''));
        });
    </script>
    </body>
    </html>
<?php
ob_end_flush();
