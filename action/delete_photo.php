<?php

require_once "../init.php";

if (isset($_SESSION['user_id'])) {

    if(empty($_GET['photo_id'])) {
        header('Location: ../index.php');
    }

    $photo = Photo::findById($_GET['photo_id']);

    if($photo->deletePhoto())
        echo "Dddddd";

    header('Location: ../edit_profile_1.php');
}
else {
    print_r($_SESSION);
}

 ?>
