<?php

    class DbObject {

        public static function findAllRecords() {       // return all records from table

            global $database;       // make $database global so you can use query() function

            // $resultSet = $database->query("SELECT * FROM users");
            // return $resultSet;
            return static::findByQuery("SELECT * FROM " . static::$dbTable . " ");      // static:: - late static binding - sprawdzić na php.net
        }

        public static function findById($id) {      // returns a record with given id

            global $database;

            $resultArray = static::findByQuery("SELECT * FROM " . static::$dbTable . " WHERE id = $id LIMIT 1");

            if(!empty($resultArray)) {
                $firstItem = array_shift($resultArray);
                return $firstItem;
            }
            else {
                return false;
            }
        }


        public static function findByQuery($sql) {      // get data from database and create objects using it (e.g. create User object using data from 'users' table)

            global $database;
            $resultSet = $database->query($sql);
            $theObjectArray = [];       // empty array, filled below

            while($row = mysqli_fetch_array($resultSet)) {
                $theObjectArray[] = static::createObject($row);
            }
            // if(count($theObjectArray) == 1) {
            //     $theObjectArray = array_shift($theObjectArray);
            // }

            return $theObjectArray;
        }

        public static function createObject($theRecord) {           // create an object and fill it with data from database

            $callingClass = get_called_class();         // get subclass

            $theObject = new $callingClass;              // create a new object of subclass


            foreach ($theRecord as $property => $value) {           // automatized creating objects (works on every class) using data from database
                if($theObject->hasProperty($property)) {
                    $theObject->$property = $value;
                }
            }

            return $theObject;
        }


        private function hasProperty($property) {       // checks if class has a given property

            $objectProperties = get_object_vars($this);         // predefined php function - returns all class' attributes

            return array_key_exists($property, $objectProperties);      // predefined php fuunction - check if $property exists in $objectProperties (if it exsts in class)
        }

        protected function properties() {       // returns all object's attributes

            $properties = array();

            foreach (static::$dbTableFields as $db_field) {
                if(property_exists($this, $db_field)) {         // object has attribute of the same name ad value of $db_field_object
                    $properties[$db_field] = $this->$db_field;      // przed $db_field dajemy "$", bo nie jest to atrybut obiektu, tylko zwykła zmienna
                 }
            }

            return $properties;
        }

        protected function cleanProperties() {      // get properties and "clean" it before sending

            global $database;

            $cleanProperties = [];

            foreach ($this->properties() as $key => $value) {
                $cleanProperties[$key] = $database->escapeString($value);
            }

            return $cleanProperties;
        }

        public function save() {

            if(isset($this->id)) {          // user exists - let's update
                $this->update();
            }       // user doesn't eixsts - let's create
            else {
                $this->create();
                return true;
            }
        }

        public function create() {

            global $database;

            $properties = $this->cleanProperties();      // get object properties in associative array

            // below - implode(array_keys()) - inserts object attrubutes' names (which are the same as in the database), separating them by comma - function will work on every various classes
            $sql = "INSERT INTO " . static::$dbTable . "(" .  implode(",", array_keys($properties))   .")" . " VALUES ('" . implode("', '", array_values($properties)) . "')";

            if($database->query($sql)) {

                $this->id = $database->theInsertId();

                return true;
            }
            else {
                return false;
            }

        }

        public function update() {

            global $database;

            $properties = $this->cleanProperties();      // get object properties in associative array

            $propertiesPairs = [];

            foreach ($properties as $key => $value) {
                $propertiesPairs[] = "{$key}='{$value}'";
            }
            
            // below - implode(array_keys()) - inserts object attrubutes' names (which are the same as in the database), separating them by comma - function will work on every various classes
            $sql = "UPDATE " . static::$dbTable . " SET " . implode(", ", $propertiesPairs) . " WHERE id = " . $database->escapeString($this->id);

            echo $sql;

            $database->query($sql);

            if(mysqli_affected_rows($database->connection) == 1) {      // check if update worked (there is one row in table changed)
                return true;
            }
            else {
                return false;
            }

        }

        public function delete() {

            global $database;

            $sql = "DELETE FROM " . static::$dbTable . " WHERE id = {$database->escapeString($this->id)} LIMIT 1";

            $database->query($sql);

            if(mysqli_affected_rows($database->connection) == 1) {      // sorawdzamy, czy update zadziałał - czy zmieniono (usunięto) jeden wiersz w tabeli
                return true;
            }
            else {
                return false;
            }
        }

        public static function countAll() {     // count all objects of class

            global $database;

            $sql = "SELECT COUNT(*) FROM " . static::$dbTable;
            $resultSet = $database->query($sql);

            $row = mysqli_fetch_array($resultSet);

            return array_shift($row);       // returning first element of array | array_shift() - delete and return first element of array
        }


    }


 ?>
