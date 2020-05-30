<?php

class Session {

    private $signedIn = false;
    public $userId;

    function __construct() {
        session_start();
        if(isset($_SESSION['user_id'])) {               // object is created after every reloading the page, so there is a need to check $_SESSION
            $this->userId = $_SESSION['user_id'];
            $this->signedIn = true;
       }
    }

    public function login($userId) {

        $_SESSION['user_id'] = $userId;
        $this->userId = $userId;
        $this->signedIn = true;
    }

    public function logout() {

        unset($_SESSION['user_id']);
        unset($this->userId);
        $this->signedIn = false;
    }

    public function isSignedIn() {          // getter, checks if user is logged in

        return $this->signedIn;
    }


}

$session = new Session();

?>
