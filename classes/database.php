<?php

    class Database {

        public $connection;

        function __construct() {
            $this->openDbConnection();        // open connection every time object is created

            $this->query("set GLOBAL sql_mode = ''");   // required to use function create() (conflicts with id)
        }

        public function openDbConnection() {

            $this->connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

            if($this->connection->connect_errno) {
                die("Error: " . $this->connection->connect_errno);
            }
        }

        public function query($query) {         // function sending query and checking for errors

            $result = $this->connection->query($query);
            $this->confirmQuery($result);

            return $result;
        }

        private function confirmQuery($result) {        // function checking for errors in result after sending a query

            if(!$result) {
                die("Query failed!" . $this->connection->error);
            }
        }

        public function escapeString($string) {

            $escapedString = $this->connection->real_escape_string($string);
            return $escapedString;
        }

        public function theInsertId() {

            return $this->connection->insert_id;    // insert_id -> returns the auto generated id used in the latest query
        }

    }

    $database = new Database();        // open db connection on every site (this file is included in header.php)
?>
