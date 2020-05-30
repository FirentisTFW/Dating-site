<?php require_once "init.php";


if (isset($_SESSION['register_next'])) {


    if (isset($_POST['register_2'])) {

        if (User::registerCheckNext()) {     // check is data is valid
            //
            $user = new User();

            // date_default_timezone_set('Europe/Warsaw');


            // data from the first part of the form
            $user->email = $_SESSION['email'];
            $user->password = password_hash($_SESSION['password'], PASSWORD_DEFAULT);                   // hash password before you send it to database
            $user->name = $_SESSION['name'];
            $user->location = $_SESSION['location'];
            $user->birth_date = $_SESSION['year'] . "-" . $_SESSION['month'] . "-" . $_SESSION['day'];
            $user->sex = $_SESSION['sex'];

            // profile photo
            $photo = new Photo();
            $photo->createFile($_FILES['file_upload']);


            if (!$photo->save()) {
                $photo_error = join("<br>", $photo->errors);        // eroory z tabeli, które wyświetlimy niżej
            } else {

                $user->caption = $_POST['caption'];
                $user->profile_photo_id = $photo->id;
                $user->looking_for = $_POST['looking_for'];
                $user->looking_for_aged = $_POST['age_min'] . "-" . $_POST['age_max'];
                $user->registry_date = date('Y-m-d H:i:s', time());
                $user->last_active = date('Y-m-d H:i:s', time());

                if ($user->save()) {

                    $photo->user_id = $user->id;     // set proper profile_id on photo
                    $photo->update();

                    unset($_SESSION['email']);
                    unset($_SESSION['password']);
                    unset($_SESSION['password2']);
                    unset($_SESSION['name']);
                    unset($_SESSION['location']);
                    unset($_SESSION['year']);
                    unset($_SESSION['month']);
                    unset($_SESSION['day']);
                    unset($_SESSION['sex']);
                    unset($_SESSION['caption']);
                    unset($_SESSION['looking_for']);
                    unset($_SESSION['age_min']);
                    unset($_SESSION['age_max']);
                    unset($_SESSION['register_next']);

                    header('Location: index.php');
                }
            }
        }
    }
} else {
    header('Location: index.php');
}

?>

<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Dating site</title>

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/main.css">

    <link href="https://fonts.googleapis.com/css2?family=Gotu&family=Roboto&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.0.min.js" integrity="sha256-xNzN2a4ltkB44Mc/Jz3pT4iU1cmeR0FkXs4pru/JxaQ=" crossorigin="anonymous"></script>
</head>

<body>

    <header>
        <p class="mx-auto mt-5 bg-danger h1 text-white pt-4 font-weight-bold text-center rounded-lg" style="width: 600px; height: 100px;">Create an account</p>
    </header>

    <p class="login-register mx-auto text-center mt-5 ">Already have an account? <br> <a href="index.php">Log in!</a></p>

    <div class="container">

        <main>
            <div id="registry_div">
                <form class="form-group p-5" method="post" enctype="multipart/form-data">

                    <p>Caption:</p> <textarea type="text" class="form-control" rows="4" name="caption" placeholder="Tell people something about you"><?php if (isset($_SESSION['caption'])) echo $_SESSION['caption'];
                                                                                                                                                        unset($_SESSION['caption']); ?></textarea><br>
                    <?php if (isset($_SESSION['err_caption'])) {
                        echo '<div class="registry_error">' . $_SESSION['err_caption'] . '</div></br>';
                        unset($_SESSION['err_caption']);
                    }
                    ?>

                    <p>Profile photo:</p>
                    <input type="file" class="" name="file_upload"><br><br>

                    <?php
                    if (isset($photo_error)) {
                        echo '<div class="registry_error">' . $photo_error . '</div></br>';
                        unset($photo_error);
                    } ?>

                    <label for="looking_for">Looking for:</label>
                    <select class="form-control col-4" id="looking_for" name="looking_for">
                        <option value="1" <?php if (isset($_SESSION['looking_for']) and $_SESSION['looking_for'] == "1") echo "selected"; ?>>Women</option>
                        <option value="2" <?php if (isset($_SESSION['looking_for']) and $_SESSION['looking_for'] == "2") echo "selected"; ?>>Men</option>
                        <option value="3" <?php if (isset($_SESSION['looking_for']) and $_SESSION['looking_for'] == "3") echo "selected"; ?>>Both</option>
                    </select><br>
                    <p class="">Aged:</p>
                    <div class="row">
                        <input type="number" min="18" class="form-control col-2 ml-3 mr-3" name="age_min" value="<?php if (isset($_SESSION['age_min'])) echo $_SESSION['age_min'];
                                                                                                                    unset($_SESSION['age_min']); ?>">to
                        <input type="number" min="18" max="120" class="form-control col-2 ml-3" name="age_max" value="<?php if (isset($_SESSION['age_max'])) echo $_SESSION['age_max'];
                                                                                                                        unset($_SESSION['age_max']); ?>"><br>
                    </div><br>
                    <?php if (isset($_SESSION['err_age'])) {
                        echo '<div class="registry_error">' . $_SESSION['err_age'] . '</div></br>';
                        unset($_SESSION['err_age']);
                    } ?>


                    <div class="row">

                        <input type="submit" class="btn btn-danger btn-lg mt-4" name="register_2" value="Register">
                        <div class="ml-5 pt-3"></div>
                    </div>
                </form>
            </div>
        </main>

    </div>


    <?php //require_once "includes/footer.php"; 
    ?>