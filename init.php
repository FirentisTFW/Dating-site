
<?php
    //
    //
    defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);      // tenary operator - | DIRECTORY_SEPARATOR - changes path sign (/, \), depending on the OS - here defined as constant

    defined('SITE_ROOT') ? null : define('SITE_ROOT', DS . 'opt' . DS . 'lampp' . DS . 'htdocs' . DS . 'tinder');       //   /opt/lampp/htdocs/tinder

    defined('INCLUDES_PATH') ? null : define('INCLUDES_PATH', SITE_ROOT . DS . 'users_images');

    require_once "dbconnect.php";
    require_once "classes/database.php";
    require_once "classes/db_object.php";
    require_once "classes/session.php";
    require_once "classes/relations.php";
    require_once "classes/matches.php";
    require_once "classes/rejections.php";
    require_once "classes/requests.php";
    require_once "classes/conversation.php";
    require_once "classes/message.php";
    require_once "classes/user.php";
    require_once "classes/photo.php";

 ?>
