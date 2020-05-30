<?php require_once "init.php" ?>
<?php
if (isset($_POST['login'])) {                    // check login data
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $userFound = User::loginUser($email, $password);

    if ($userFound) {

        $session->login($userFound->id);        // log user into session

        header("Location: main_find_matches.php");
    } else {
        $message = "Your password or username is incorrect";
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
        <p class="mx-auto mt-5 bg-danger h1 text-white pt-4 font-weight-bold text-center rounded-lg" style="width: 600px; height: 100px;">Log in</p>
    </header>

    <p class="login-register mx-auto text-center mt-5 ">Don't have an account yet? <br> <a href="register.php">Create one for free!</a></p>

    <div class="container">

        <main>
            <div id="login_div">
                <form class="form-group p-5" method="post">
                    <p class="pt-4">E-mail:</p> <input type="text" class="form-control" name="email"><br><br>
                    <p>Password:</p> <input type="password" class="form-control" name="password"><br><br>
                    <div class="row">

                        <input type="submit" class="btn btn-danger btn-lg" name="login" value="Log in">
                        <div class="ml-5 pt-3"> <?php if (isset($message)) echo $message; ?></div>
                    </div>
                </form>
            </div>
        </main>

    </div>

    <?php //require_once "includes/footer.php" 
    ?>