<?php

class Photo extends DbObject {

    protected static $dbTable = "photos";
    protected static $dbTableFields = ['id','filename', 'user_id', 'date'];
    public $id;
    public $filename;
    public $user_id;
    public $date;

    public $tmpPath;    // temporary path for photo (before it goes to main folder)
    public $uploadDirectory = "users_images";
    public $errors = [];   // table with errors while uploading


    public function createFile($file) {


        if(empty($file) || !$_FILES || !is_array($_FILES)) {        // error checking
            $this->errors[] = "There was no fle uploaded here";
            return false;
        }
        elseif($file['error'] != 0) {                     // error checking c.d.
            $this->errors[] = "Error!";
            return false;
        }
        else {                                                    // everything is fine, upload the file
            $this->filename = basename($file['name']);        // get file name
            $this->tmpPath = $file['tmp_name'];
            $this->user_id = 0;                          // temporary id (because user isn't created yet), it's fixed after creating the user
            $this->date = date('Y-m-d H:i:s', time());
            list($width, $height, $type, $attr) = getimagesize($this->tmpPath);

            if($width < 350 or $height < 400) {
                $this->errors[] = "Photo size must be at least 350x400px!";
                return false;
            }
        }
    }

    public function save() {        // overwrite DbObject's class function

        if(!empty($this->errors)) {
            return false;
        }

        if(empty($this->tmpPath) || empty($this->filename)) {
            $this->errors[] = "The file is not available";
            return false;
        }

        $filePath = SITE_ROOT . DS . $this->uploadDirectory . DS . $this->filename;

        if(file_exists($this->filename)) {
            $this->errors[] = "The file named {$this->filename} alreay exists";
            return false;
        }

        if(move_uploaded_file($this->tmpPath, $filePath)) {      // move file to parmanent location

            if($this->create()) {
                unset($this->tempPath);
                return true;
            }
            else {                                                // last error checking
                $this->errors[] = "Error! The file directory probably does not have permission.";
                return false;
            }
        }
    }

    public static function findAllOfOneUser($user_id) {            // returns all photos of one user

        $sql = "SELECT * FROM " . self::$dbTable . " WHERE user_id = $user_id";

        $result = self::findByQuery($sql);

        return $result;
    }

    public function deletePhoto() {

        if ($this->delete()) {      // record deleted from the table -> now delete the photo from the folder

            $filePath = SITE_ROOT . DS . "images" . DS . $this->filename;
            return unlink($filePath) ? true : false;            // built-in php function for deleting the file
        }
        else {
            return false;
        }
    }

}

 ?>
