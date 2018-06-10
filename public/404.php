<?php
/**
 * User: Erik Wilson
 * Date: 17-May-18
 * Time: 23:59
 */
//echo 'Page not found';

?>
<!DOCTYPE html>
<html lang="en">
<?php
include TEMPLATE_PATH . '/htmlhead.php';
?>
<body>
<button id="mybutton">click me</button>
<script>
    jQuery('#mybutton').on('click', function () {
        // console.log('here');
        jQuery.ajax('api/v1', {
            method: 'GET',
            data: {
                user: 'erik@wilson.com',
                secret: 'giailuhflkjdhalkuhg'
            }
        }).done(function (data) {
            console.log(data);
        }).fail(function () {
            console.log('fuck');
        });
    });
</script>
</body>
</html>
