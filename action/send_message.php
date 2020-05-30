<?php

    require_once "../init.php";

    $conversation_exists = Conversation::findByQuery("SELECT * FROM conversations WHERE (first_user_id = {$_POST['target_id']} AND second_user_id = {$_SESSION['user_id']})
        OR (first_user_id = {$_SESSION['user_id']} AND second_user_id = {$_POST['target_id']}) LIMIT 1");



    if(empty($conversation_exists)) {    // if conversation doesn't exists (it is the first message), create it

        $conversation = new Conversation();

        $conversation->first_user_id = $_SESSION['user_id'];
        $conversation->second_user_id = $_POST['target_id'];
        $conversation->date = date('Y-m-d H:i:s', time());

        $conversation->save();
    }
    else {
        $conversation = array_shift($conversation_exists);
    }


    if(!empty($_POST['content'])) {    // create a message, message can't be empty

        $message = new Message();

        $message->conversation_id = $conversation->id;
        $message->sender_id = $_SESSION['user_id'];
        $message->content = $_POST['content'];
        $message->date = date('Y-m-d H:i:s', time());
        $message->seen = false;

        $message->save();
    }

    if (empty($conversation_exists)) {              // if the conversation was just created, refrsh the page to load needed functions
        echo "A";
    }

 ?>
