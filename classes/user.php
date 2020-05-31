<?php

class User extends DbObject {

    protected static $dbTable = "users";
    protected static $dbTableFields = ['id', 'email', 'password', 'name', 'profile_photo_id', 'birth_date',
    'registry_date', 'caption', 'sex', 'looking_for', 'looking_for_aged', 'location', 'last_active'];
    public $id;
    public $email;
    public $password;
    public $name;
    public $profile_photo_id;
    public $birth_date;
    public $registry_date;
    public $caption;
    public $sex;
    public $looking_for;
    public $looking_for_aged;
    public $location;
    public $last_active;

    public static function loginUser($email, $password) {

        global $database;

        $email = $database->escapeString($email);
        $password = $database->escapeString($password);

        $sql = "SELECT * FROM " . self::$dbTable . " WHERE email = '{$email}' LIMIT 1";  //AND password = '{$password}' 
        $result = $database->query($sql);
        $resultArray = $result->fetch_assoc();
        if(password_verify($password, $resultArray['password']) == true) {              // check if password matches hashed password in database

            $result = self::findByQuery($sql);
    
            if(!empty($result)) {
                $firstItem = array_shift($result);
                return $firstItem;
            }
            else {
                return false;
            }
        } 
        else {
            return false;
        }
    }

    public static function registerCheck() {

        global $database;

        $_SESSION['email'] = $_POST['email'];
        $_SESSION['password'] = $_POST['password'];
        $_SESSION['password2'] = $_POST['password2'];
        $_SESSION['name'] = $_POST['name'];
        $_SESSION['location'] = $_POST['location'];
        $_SESSION['day'] = $_POST['day'];
        $_SESSION['month'] = $_POST['month'];
        $_SESSION['year'] = $_POST['year'];
        $_SESSION['sex'] = $_POST['sex'];


        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ) {

            $_SESSION['err_email'] = 'Please enter a valid e-mail address.';
            return false;
        }
        // e-mail address check below
        $email = $_POST['email'];
        $sql = "SELECT COUNT(*) FROM " . self::$dbTable . " WHERE email = '{$email}'";
        $test = $database->query($sql);
        $counter = mysqli_fetch_array($test);
        $counter = array_shift($counter);
        if ($counter > 0) {
            $_SESSION['err_email'] = 'An account with this e-mail address already exists.';
            return false;
        }
        if (strlen($_POST['password']) < 6 ) {

            $_SESSION['err_password'] = 'Password must be at least 6 characters long.';
            return false;
        }
        if ($_POST['password2'] != $_POST['password'] ) {

            $_SESSION['err_password'] = 'Given passwords are not the same.';
            return false;
        }
        if (strlen($_POST['name']) < 1 ) {

            $_SESSION['err_name'] = 'This field cannot be empty.';
            return false;
        }
        if (strlen($_POST['location']) < 1 ) {

            $_SESSION['err_location'] = 'This field cannot be empty.';
            return false;
        }
        if (!checkdate($_POST['month'], $_POST['day'], $_POST['year'])) {

            $_SESSION['err_birth_date'] = "Birth date is not valid.";
            return false;
        }

        $birth_date = $_POST['year'] . "-" . $_POST['month'] . "-" . $_POST['day'];

        $age = User::getAge($birth_date);

        if($age < 18) {
            $_SESSION['err_birth_date'] = "You must be at least 18 years old.";
            return false;
        }

        return true;
    }


    public static function registerCheckNext() {

        $_SESSION['caption'] = $_POST['caption'];
        $_SESSION['looking_for'] = $_POST['looking_for'];
        $_SESSION['age_min'] = $_POST['age_min'];
        $_SESSION['age_max'] = $_POST['age_max'];

        if (strlen($_POST['age_min']) < 1 or strlen($_POST['age_max']) < 1) {

            $_SESSION['err_age'] = 'These fields cannot be empty.';
            return false;
        }

        if($_POST['age_min'] > $_POST['age_max']) {
            $temp = $_POST['age_max'];
            $_POST['age_max'] = $_POST['age_min'];
            $_POST['age_min'] = $temp;
        }

        return true;
    }


    public static function getAge($birth_date) {

        $then = date('Ymd', strtotime($birth_date));
        $diff = date('Ymd') - $then;
        $age = substr($diff, 0, -4);

        return $age;
    }

    public function getProfilePhoto() {

        $photo = Photo::findById($this->profile_photo_id);

        return $photo;
    }

    public function findMatches() {

        $sql = "SELECT * FROM matches WHERE first_user_id = $this->id OR second_user_id = $this->id";
        $matches = Matches::findByQuery($sql);

        return $matches;
    }

    public function findConversations() {

        $sql = "SELECT * FROM conversations WHERE first_user_id = $this->id OR second_user_id = $this->id";
        $conversations = Conversation::findByQuery($sql);

        return $conversations;
    }

    public function findPossibleMatches($howMany) {


        $matches = Matches::findRelationsByQuery($this->id);
        $requests = Requests::findRelationsByQuery($this->id);
        $rejections = Rejections::findRelationsByQuery($this->id);

        $sql = "SELECT * FROM users WHERE sex NOT LIKE $this->looking_for LIMIT $howMany";         // take records only with desired sex (not like means like -> different number while registering)

        $users = self::findByQuery($sql);

        // print_r($requests);

        $possible_users = [];

        $counter = count($users);

        for ($i=0; $i < $counter; $i++) {

            $date = substr($users[$i]->birth_date, 0, 10);
            $then = date('Ymd', strtotime($users[$i]->birth_date));
            $diff = date('Ymd') - $then;
            $age = substr($diff, 0, -4);

            $age_min = 18;          // ZROBIĆ TO PORZADNIE!
            $age_max = 28;

            $flag = false;

            for ($j=0; $j < count($matches); $j++) {
                if($matches[$j]->second_user_id == $users[$i]->id or $matches[$j]->first_user_id == $users[$i]->id) {           // check if these people are already matched
                    $flag = true;
                    break;
                }
            }
            if ($flag == true) { continue; }                   //   there was a match -> don't add this user to the table, go to the next user
            for ($j=0; $j < count($requests); $j++) {
                if($requests[$j]->second_user_id == $users[$i]->id) {           // check if the request was already sent
                    $flag = true;
                    break;
                }
            }
            if ($flag == true) { continue; }
            for ($j=0; $j < count($rejections); $j++) {
                if($rejections[$j]->second_user_id == $users[$i]->id or $rejections[$j]->first_user_id == $users[$i]->id) {           // check if this person already rejected $this
                    $flag = true;
                    break;
                }
            }
            if ($flag == true) { continue; }


            // if ($users[$i]->location == $this->location and $age < $age_max and $age > $age_min) {      // age and location check
            //     array_push($possible_users, $users[$i]);
            // }
            if($this->id != $users[$i]->id)
                array_push($possible_users, $users[$i]);
        }

        return $possible_users;
    }


}

 ?>
