<?php 

    require_once "../init.php";

    if(isset($_POST['conversation_id']) and isset($_POST['first_message_id'])) {
        
        $conversation = Conversation::findById($_POST['conversation_id']);

        $sql = "SELECT * FROM messages WHERE conversation_id = $conversation->id ORDER BY id ASC LIMIT 1";
        $the_oldest_message = Message::findByQuery($sql);

    //        echo "true";

        if(array_shift($the_oldest_message)->id < $_POST['first_message_id']) {
            echo "true";
        }
        else {
            echo "false";
        }
    }

?>