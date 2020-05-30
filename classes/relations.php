<?php

    class Relations extends DbObject {


        public static function findRelationsByQuery($id) {

            $sql = "SELECT * FROM " . static::$dbTable . " WHERE first_user_id = $id or second_user_id = $id";

            return static::findByQuery($sql);
        }
    }

 ?>
