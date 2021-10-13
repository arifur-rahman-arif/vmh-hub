<?php get_header('header.php')?>

<!--======================== Start Login Area ========================-->
<section class="login_main">
    <div class="container">
        <!-- Start Background Overly -->
        <div class="login_background">
            <img src="<?php echo VMH_URL . 'Assets/images/login/circle.png' ?>" alt="" />
        </div>
        <!-- End Background Overly -->

        <?php

if (isset($_GET['key']) && $_GET['key']) {
    $key = sanitize_text_field($_GET['key']);
    if (array_key_exists($key, $_SESSION)) {
        $userData = $_SESSION[$key];
        if (is_array($userData)) {
            $createUser = createUser($userData);
            if ($createUser['response'] == 'success') {
                echo '
                    <div class="card border-success">
                        <div class="card-body">
                            ' . $createUser['message'] . '
                        </div>
                    </div>
                ';
            } else {
                echo '
                    <div class="card border-danger">
                        <div class="card-body">
                            ' . $createUser['message'] . '
                        </div>
                    </div>
                ';
            }
        }

    } else {
        echo '
            <div class="card border-danger">
                <div class="card-body">
                    Key is not found in session.
                </div>
            </div>
        ';
    }

} else {
    echo '
        <div class="card border-danger">
            <div class="card-body">
                Session key is expired or URL is invalid
            </div>
        </div>
    ';
}
?>
    </div>

</section>
<!--======================== End Login Area ========================-->

<?php session_destroy()?>

<?php get_footer('header.php')?>