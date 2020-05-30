<?php

require_once "../init.php";

if (isset($_GET['rejected_user_id'])) {

    $rejection = new Rejections();

    $rejection->first_user_id = $_SESSION['user_id'];
    $rejection->second_user_id = $_GET['rejected_user_id'];

    $rejection->save();

    // below - delete - if exists - second user's request since it's useless anymore

    $f_u_id = $_GET['rejected_user_id'];
    $s_u_id = $_SESSION['user_id'];
    $sql = "SELECT * FROM requests WHERE first_user_id = $f_u_id AND second_user_id = $s_u_id ";
    $possible_request = Requests::findByQuery($sql);

    if(!empty($possible_request)) {
        $request = array_shift($possible_request);
        $request->delete();
    }

    header('Location: ../main_find_matches.php');
}

 ?>
