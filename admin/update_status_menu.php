<?php

require_once '../function.php';

if (isset($_POST['id_menu']) && isset($_POST['status'])) {

    update_status_menu(
        $_POST['id_menu'],
        $_POST['status']
    );
}

header("Location:index.php?page=admin_menu");
exit;