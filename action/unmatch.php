<?php

require_once "../init.php";

if (isset($_SESSION['user_id']) and isset($_GET['user_id'])) {
    
        $user = User::findById($_SESSION['user_id']);
        $matches = $user->findMatches();

        for ($i=0; $i < count($matches); $i++) {
            if($matches[$i]->first_user_id == $_GET['user_id'] or $matches[$i]->second_user_id == $_GET['user_id']) {
                $matches[$i]->delete();
                break;
            }
        }

        header('Location: ../main_matches.php');
}
else {
    header('Location: ../index.php');
}

 ?>
