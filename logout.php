<?php require_once "init.php" ?>
<?php
    if(isset($_SESSION['user_id'])) {

        $session->logout();
    }
    header('Location: index.php');
 ?>
