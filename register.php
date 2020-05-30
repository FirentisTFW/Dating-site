<?php require_once "init.php";


if (isset($_POST['register'])) {

    if (User::registerCheck()) {     // check is data is valid

        $_SESSION['register_next'] = true;
        header('Location: register_2.php');
    }
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

    <p class="login-register mx-auto text-center mt-5 ">Already have an account?<br> <a href="index.php">Log in!</a></p>

    <div class="container">

        <main>
            <div id="registry_div">
                <form class="form-group p-5" method="post">
                    <p class="pt-4">E-mail:</p> <input type="text" class="form-control col-6" name="email" value="<?php if (isset($_SESSION['email'])) echo $_SESSION['email']; ?>"><br>
                    <?php if (isset($_SESSION['err_email'])) {
                        echo '<div class="registry_error">' . $_SESSION['err_email'] . '</div></br>';
                        unset($_SESSION['err_email']);
                    } ?>

                    <p>Password:</p> <input type="password" class="form-control col-6" name="password" value="<?php if (isset($_SESSION['password'])) echo $_SESSION['password']; ?>"><br>
                    <p>Repeat password:</p> <input type="password" class="form-control col-6" name="password2" value="<?php if (isset($_SESSION['password2'])) echo $_SESSION['password2']; ?>"><br>
                    <?php if (isset($_SESSION['err_password'])) {
                        echo '<div class="registry_error">' . $_SESSION['err_password'] . '</div></br>';
                        unset($_SESSION['err_password']);
                    } ?>

                    <p>Name:</p> <input type="text" class="form-control col-6" name="name" value="<?php if (isset($_SESSION['name'])) echo $_SESSION['name']; ?>"><br>
                    <?php if (isset($_SESSION['err_name'])) {
                        echo '<div class="registry_error">' . $_SESSION['err_name'] . '</div></br>';
                        unset($_SESSION['err_name']);
                    } ?>

                    <p>Location:</p> <input type="text" class="form-control col-6" name="location" value="<?php if (isset($_SESSION['location'])) echo $_SESSION['location']; ?>"><br>
                    <?php if (isset($_SESSION['err_location'])) {
                        echo '<div class="registry_error">' . $_SESSION['err_location'] . '</div></br>';
                        unset($_SESSION['err_location']);
                    } ?>

                    <p class="">Birth date:</p>
                    <div class="row">
                        <select class="ml-3 form-control col-2" name="month">
                            <option value="1" <?php if (isset($_SESSION['month']) and $_SESSION['month'] == '1') echo 'selected'; ?>>Jan</option>
                            <option value="2" <?php if (isset($_SESSION['month']) and $_SESSION['month'] == '2') echo 'selected'; ?>>Feb</option>
                            <option value="3" <?php if (isset($_SESSION['month']) and $_SESSION['month'] == '3') echo 'selected'; ?>>Mar</option>
                            <option value="4" <?php if (isset($_SESSION['month']) and $_SESSION['month'] == '4') echo 'selected'; ?>>Apr</option>
                            <option value="5" <?php if (isset($_SESSION['month']) and $_SESSION['month'] == '5') echo 'selected'; ?>>May</option>
                            <option value="6" <?php if (isset($_SESSION['month']) and $_SESSION['month'] == '6') echo 'selected'; ?>>Jun</option>
                            <option value="7" <?php if (isset($_SESSION['month']) and $_SESSION['month'] == '7') echo 'selected'; ?>>Jul</option>
                            <option value="8" <?php if (isset($_SESSION['month']) and $_SESSION['month'] == '8') echo 'selected'; ?>>Aug</option>
                            <option value="9" <?php if (isset($_SESSION['month']) and $_SESSION['month'] == '9') echo 'selected'; ?>>Sep</option>
                            <option value="10" <?php if (isset($_SESSION['month']) and $_SESSION['month'] == '10') echo 'selected'; ?>>Oct</option>
                            <option value="11" <?php if (isset($_SESSION['month']) and $_SESSION['month'] == '11') echo 'selected'; ?>>Nov</option>
                            <option value="12" <?php if (isset($_SESSION['month']) and $_SESSION['month'] == '12') echo 'selected'; ?>>Dec</option>
                        </select>
                        <select class="ml-3 form-control col-2" name="day">
                            <?php for ($i = 1; $i < 32; $i++) { ?>
                                <option value='<?php echo $i; ?>' <?php if (isset($_SESSION['day']) and $_SESSION['day'] == $i) echo 'selected'; ?>><?php echo $i ?></option>;
                            <?php } ?>
                            <?php //if(isset($_SESSION['day']) and $_SESSION['day'] == $i) echo 'selected';
                            ?>
                        </select>
                        <select class="ml-3 form-control col-2" name="year">
                            <?php for ($i = 2020; $i >= 1900; $i--) { ?>
                                <option value='<?php echo $i; ?>' <?php if (isset($_SESSION['year']) and $_SESSION['year'] == $i) echo 'selected'; ?>><?php echo $i ?></option>;
                            <?php } ?>
                        </select>
                    </div><br>
                    <?php if (isset($_SESSION['err_birth_date'])) {
                        echo '<div class="registry_error">' . $_SESSION['err_birth_date'] . '</div></br>';
                        unset($_SESSION['err_birth_date']);
                    } ?>

                    <label for="sex">Sex:</label>
                    <select class="form-control col-4" id="sex" name="sex">
                        <option value="1" <?php if (isset($_SESSION['sex']) and $_SESSION['sex'] == "1") echo "selected"; ?>>Man</option>
                        <option value="2" <?php if (isset($_SESSION['sex']) and $_SESSION['sex'] == "2") echo "selected"; ?>>Woman</option>
                    </select> <br>

                    <div class="row">

                        <input type="submit" class="btn btn-danger btn-lg mt-4" name="register" value="Register">
                        <div class="ml-5 pt-3"></div>
                    </div>
                </form>
            </div>
        </main>

    </div>


    <?php // require_once "includes/footer.php" 
    ?>