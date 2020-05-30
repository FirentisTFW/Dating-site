<?php

class Requests extends Relations {

    protected static $dbTable = "requests";
    protected static $dbTableFields = ['id', 'first_user_id', 'second_user_id'];
    public $id;
    public $first_user_id;
    public $second_user_id;
}

 ?>
