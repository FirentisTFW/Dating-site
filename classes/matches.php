<?php

class Matches extends Relations {

    public static $dbTable = "matches";
    public static $dbTableFields = ['id', 'first_user_id', 'second_user_id'];

    public $id;
    public $first_user_id;
    public $second_user_id;
}

 ?>
