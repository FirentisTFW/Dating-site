<?php

require_once "../init.php";

if (isset($_GET['requested_user_id'])) {

    $f_u_id = $_GET['requested_user_id'];
    $s_u_id = $_SESSION['user_id'];
    $sql = "SELECT * FROM requests WHERE first_user_id = $f_u_id AND second_user_id = $s_u_id ";        // check if requested person requested requesting person before
    $possible_request = Requests::findByQuery($sql);

    if(!empty($possible_request)) {         // there is request created by the requested person -> it's a match!
        $old_request = array_shift($possible_request);
        $old_request->delete();

        // create a match!

        $match = new Matches();
        $match->first_user_id = $_GET['requested_user_id'];
        $match->second_user_id = $_SESSION['user_id'];

        $match->save();
    }
    else {      // no match -> create request
        $request = new Requests();
        $request->first_user_id = $_SESSION['user_id'];
        $request->second_user_id = $_GET['requested_user_id'];

        $request->save();
    }

   // header('Location: ../main_find_matches.php');
}

 ?>
