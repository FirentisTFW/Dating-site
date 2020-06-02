<?php

require_once "../init.php";

if (!empty($_POST['conversation_id'])) {

    $conversation = Conversation::findById($_POST['conversation_id']);

    // echo $_POST['older'];

    if ($_POST['older'] == "false")                                                                      // show new messages
        $new_messages = $conversation->checkForNewMessages($_POST['last_message_id']);
    else {                                                                                              // load older messages
        $older_messages = $conversation->findMessagesInConversation(30, $_POST['first_message_id']);
        $conversation->displayMessages($older_messages);
    }
}

?>
