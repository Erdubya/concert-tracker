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

cookie_loader($dbh);

$userid = check_login();

// set the page title
$pageTitle = "Concerts - Concert Tracker";

// include script for checkbox formatting
$extraIncludes = array(
    "<script src='js/bootstrap-checkbox.js' defer></script>"
);

// get list of artist names
$stmt = $dbh->prepare("SELECT artist_id, name FROM artist WHERE user_id=:userid ORDER BY " . artist_sort_sql() . " ASC ");
$stmt->bindParam(":userid", $userid);
$stmt->execute();
$artist_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
$artist_arr   = [];
ob_start();
foreach ($artist_fetch as $result) {
    $artist_arr[$result['artist_id']] = $result['name'];
    echo "<option value='" . $result['artist_id'] . "'>" . $result['name'] . "</option>";
}
$artist_list = ob_get_clean();
//var_dump($artist_arr);
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
        <button type="button" class="btn btn-primary" data-toggle='modal' data-target='#concert-modal' data-attend='1'>
            Add New
        </button>
        <div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                Select... <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li><a href="#">All</a></li>
                <li><a href="#">None</a></li>
            </ul>
        </div>
        <div class="btn-group">
            <button disabled type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                Action... <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li><a href="#">Edit</a></li>
                <li><a href="#">Delete</a></li>
            </ul>
        </div>
        <!-- Concert List -->
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th class="col-xs-2">Date</th>
                    <th class="col-xs-6">Artist</th>
                    <th class="col-xs-3">City</th>
                    <th class="col-xs-1 text-center">Attend</th>
                </tr>
                </thead>
                <tbody>
                <?php

                // Get list of concerts
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
                          a.user_id = :user
                        GROUP BY
                          c.concert_id
                        ORDER BY
                          c.date DESC;";

                $stmt = $dbh->prepare($sql);
                $stmt->bindParam(":user", $_SESSION['user']);
                $stmt->execute();

                // display concerts
                foreach ($stmt->fetchAll() as $key => $result) {
                    $current_date = date("Y-m-d");

                    // Check if the show was in the past and highlight the row accordingly
                    if ($result['date'] < $current_date && ! $result['attend']) {
                        echo "<tr class='warning'>";
                    } elseif ($result['date'] < $current_date) {
                        echo "<tr class='active'>";
                    } else {
                        echo "<tr>";
                    }

                    //date column, with data for edit display
                    echo "<td>" . $result['date'] . "<br>";
                    echo "<span class='glyphicon glyphicon-unchecked'></span> ";
                    echo "<span class='glyphicon glyphicon-pencil' data-toggle='modal' data-target='#concert-modal'
                            data-id='" . $result['concert_id'] . "'
                            data-date='" . $result['date'] . "'
                            data-o_artists='" . $result['o_artists'] . "'
                            data-p_artists='" . $result['p_artists'] . "'
                            data-city='" . $result['city'] . "' 
                            data-venue='" . $result['venue'] . "' 
                            data-notes='" . $result['notes'] . "' 
                            data-attend='" . $result['attend'] . "'></span> ";
                    echo "<span class='glyphicon glyphicon-remove-circle' data-toggle='modal' data-target='#delete-modal'
                            data-id='" . $result['concert_id'] . "'></span>";
                    echo "</td>";

                    // artists and city
                    $primaries = "";
                    $openers   = "";
                    echo "<td>";
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
                    echo strlen($primaries) > 160 ? substr($primaries, 0, 160) . "..." : $primaries;
                    echo "<br><small>";
                    echo strlen($openers) > 160 ? substr($openers, 0, 160) . "..." : $openers;
                    echo "</small></td>";
                    echo "<td>" . $result['city'];
                    echo "<br><small>" . $result['venue'] . "</small></td>";

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
                                <label for="p-artist-edit"
                                       class="control-label">Primary Artist(s)</label>
                                <select id="p-artist-edit" name="p_artist[]"
                                        class="form-control" multiple="multiple">
                                    <?php echo $artist_list; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="o-artist-edit"
                                       class="control-label">Opening Artist(s)</label>
                                <select id="o-artist-edit" name="o_artist[]"
                                        class="form-control" multiple="multiple">
                                    <?php echo $artist_list; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="city-edit"
                                       class="control-label">City</label>
                                <input type="text" id="city-edit"
                                       name="city" class="form-control"
                                       maxlength="50">
                            </div>
                            <div class="form-group">
                                <label for="venue-edit"
                                       class="control-label">Venue</label>
                                <input type="text" id="venue-edit"
                                       name="venue" class="form-control"
                                       maxlength="50">
                            </div>
                            <div class="form-group">
                                <label for="notes-edit"
                                       class="control-label">Notes</label>
                                <textarea id="notes-edit" name="notes"
                                          class="form-control"
                                          maxlength="500"
                                          rows="5"></textarea>
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
                        <button type="submit" class="btn btn-primary" id="modal-edit-button"
                                form="edit-form" name="update">Save Changes
                        </button>
                        <button type="submit" class="btn btn-success" id="modal-add-button"
                                form="edit-form" name="add">Add Concert
                        </button>
                        <button type="button" class="btn btn-default" id="modal-cancel-button"
                                data-dismiss="modal">Close
                        </button>
                        <button type="button" class="btn btn-danger" id="modal-delete-button"
                                data-dismiss="modal">Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- DELETE MODAL -->
        <div class="modal fade" id="delete-modal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close"
                                data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel2">Confirm Delete</h4>
                    </div>
                    <div class="modal-body">
                        <form id="delete-form" action="edit-concert.php" method="post">
                            <input hidden title="id" type="text" id="concert-idd" name="id">
                        </form>
                        Are you sure you want to delete this concert? This cannot be undone.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel
                        </button>
                        <button type="submit" class="btn btn-danger" form="delete-form" name="delete">Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Simple footer -->
    <?php
    include 'footer.php';
    echo $footer;
    ?>

    <script>
        function getKeyByValue(object, value) {
            return Object.keys(object).find(key => object[key] === value);
        }

        artist_array = <?php echo json_encode($artist_arr) ?>;
        modaldiv = $('#concert-modal');

        $(document).ready(function () {
            // Set navbar active highlighting
            // get current URL path and assign 'active' class to navbar
            let pathname = new URL(window.location.href).pathname.split('/').pop();
            if (pathname !== "") {
                $('.nav > li > a[href="' + pathname + '"]').parent().addClass('active');
            }

            // create yes/no toggle checkboxes
            $(':checkbox').checkboxpicker();

            // create artist selects for edit modal
            $('#p-artist-edit').select2({
                dropdownParent: modaldiv,
                theme: "bootstrap",
                width: '100%'
            });
            $('#o-artist-edit').select2({
                dropdownParent: modaldiv,
                theme: "bootstrap",
                width: '100%'
            });
        });

        // Set dynamic data in the edit modal
        modaldiv.on('show.bs.modal', function (event) {
            let link = $(event.relatedTarget); // Item that triggered the modal
            let p_artist_keys = [];
            let o_artist_keys = [];

            // Extract info from data-* attributes, if it exists
            let date = link.data('date');
            let p_artist = link.data('p_artists');
            let o_artist = link.data('o_artists');
            let city = link.data('city');
            let venue = link.data('venue');
            let notes = link.data('notes');
            let attend = link.data('attend');
            let id = link.data('id');

            // if edit or create dialog
            if (link.is('span')) {
                // set title
                $('#myModalLabel').text('Edit Concert');
                jQuery('#edit-form').attr('action', 'edit-concert.php');

                // get selected artists
                for (let artist of p_artist) {
                    p_artist_keys.push(getKeyByValue(artist_array, artist));
                }
                for (let artist of o_artist) {
                    o_artist_keys.push(getKeyByValue(artist_array, artist));
                }

                // display correct buttons
                jQuery('#modal-edit-button').show();
                jQuery('#modal-delete-button').show();
                jQuery('#modal-add-button').hide();
            } else {
                // set title
                $('#myModalLabel').text('Add Concert');
                jQuery('#edit-form').attr('action', 'add-concert.php');

                // display correct buttons
                jQuery('#modal-edit-button').hide();
                jQuery('#modal-delete-button').hide();
                jQuery('#modal-add-button').show();
            }

            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            let modal = $(this);
            modal.find('.modal-body #date-edit').val(date);
            modal.find('.modal-body #city-edit').val(city);
            modal.find('.modal-body #venue-edit').val(venue);
            modal.find('.modal-body #notes-edit').val(notes);
            if (attend === 1) {
                modal.find('.modal-body #attend-edit').prop('checked', true);
            } else {
                modal.find('.modal-body #attend-edit').prop('checked', false);
            }
            modal.find('.modal-body #concert-id').val(id);

            // set selected artists
            $('#p-artist-edit').val(p_artist_keys).trigger('change');
            $('#o-artist-edit').val(o_artist_keys).trigger('change');
        });

        // Set concert ID for delete modal
        jQuery('#delete-modal').on('show.bs.modal', function (event) {
            let id = $(event.relatedTarget).data('id');
            $(this).find('.modal-body #concert-idd').val(id);
        });

        // edit modal delete defers to delete modal
        jQuery('#modal-delete-button').click(function () {
            jQuery('#delete-modal').modal('show');
        });

        // Disallow hard line breaks in edit textareas
        $('textarea').on('keyup', function () {
            $(this).val($(this).val().replace(/[\r\n\v]+/g, ''));
        });
    </script>
    </body>
    </html>
<?php
ob_end_flush();
