<?php

class Message extends DbObject
{
    protected static $dbTable = "messages";
    protected static $dbTableFields = ['id','conversation_id', 'sender_id', 'content', 'date', 'seen'];
    public $id;
    public $conversation_id;
    public $sender_id;
    public $content;
    public $date;
    public $seen;

}


 ?>
