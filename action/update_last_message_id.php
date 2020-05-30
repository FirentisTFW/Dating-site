<?php

require_once "../init.php";

    if(!empty($_POST['conversation_id'])) {

        $conversation = Conversation::findById($_POST['conversation_id']);

        echo $conversation->getLastMessageId();                                            // send last message's id

        // if($messages[count($messages)-1]->id != $_POST['last_message_id']) {            // there is a new message (or messages)
            
        // }

    }

 ?>
