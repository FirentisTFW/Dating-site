<?php

class Conversation extends DbObject
{
    protected static $dbTable = "conversations";
    protected static $dbTableFields = ['id', 'first_user_id', 'second_user_id', 'date'];
    public $id;
    public $first_user_id;
    public $second_user_id;
    public $date;

    public function findAllMessagesInConversation() {

        $sql = "SELECT * FROM messages WHERE conversation_id = $this->id ORDER BY 1 ASC";

        $messages = Message::findByQuery($sql);

        return $messages;
    }

    public function findMessagesInConversation($howMany, $fromId = null) {

        if(!isset($fromId))                             // fromId not specified -> get messages starting from the most recent one
            $sql = "SELECT * FROM messages WHERE conversation_id = $this->id ORDER BY id DESC LIMIT $howMany";
        else
            $sql = "SELECT * FROM messages WHERE conversation_id = $this->id AND id < $fromId ORDER BY id DESC LIMIT $howMany";

        $messages = Message::findByQuery($sql);

        $messages = array_reverse($messages);

        return $messages;
    }


    public function displayMessages($messages) {

        foreach($messages as $message) {
            if($message->sender_id == $_SESSION['user_id'])
                echo "<div class='messages-single-message messages-single-message-sent' id='messageId_{$message->id}'>{$message->content}</div>";
            else
                echo "<div class='messages-single-message messages-single-message-received' id='messageId_{$message->id}'>{$message->content}</div>";
        }
    }

    public function getLastMessageId() {

        $message = $this->findMessagesInConversation(1);
        $message = array_shift($message);
        return $message->id;
    }

    public function checkForNewMessages($lastMessageId) {

        $messages = $this->findMessagesInConversation(40);                         // search for 40 new messages. If there's more, you can load them after displaying most recent 40

        for ($i = count($messages) - 1; $i >= 0; $i--) {
            if ($messages[$i]->id == $lastMessageId) {
                $this->displayMessages(array_slice($messages, $i+1));              // return new messages
                break;
            }
        }

        return false;                                   
    }

    public function areThereMoreMessages($fromId) {
        
        $areThere = $this->findMessagesInConversation(1, $fromId);

        if(!empty($areThere))
            return true;
        
        return false;
    }
}

 ?>
