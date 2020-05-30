<?php require_once "includes/header.php";


    if(isset($_POST['register'])) {

        if(User::createUserCheck()) {     // check is data is valid

            $user = new User();

            // date_default_timezone_set('Europe/Warsaw');

            $user->name = $_POST['name'];
            $user->email = $_POST['email'];
            $user->password = $_POST['password'];
            $user->sex = $_POST['sex'];
            $user->location = $_POST['location'];
            $user->looking_for = $_POST['looking_for'];
            $user->looking_for_aged = $_POST['age_min'] . "-" . $_POST['age_max'];
            $user->birth_date = $_POST['year'] . "-" . $_POST['month'] . "-" . $_POST['day'];
            $user->caption = "asdsada";
            $user->registry_date = date('Y-m-d H:i:s', time());
            $user->last_active = date('Y-m-d H:i:s', time());
            $user->profile_photo_id = 1;

            // echo $user->registry_date;

            $user->save();

        }

    }

 ?>


<header>
    <p class="mx-auto mt-5 bg-danger h1 text-white pt-4 font-weight-bold text-center rounded-lg" style="width: 300px; height: 140px;">Create an account</p>
</header>

<div class="container">

 <main>
     <div id="registry_div">
         <form class="form-group p-5" method="post">
             <p class="pt-4">E-mail:</p> <input type="text" class="form-control col-6" name="email" value="<?php if(isset($_SESSION['email'])) echo $_SESSION['email']; unset($_SESSION['email']); ?>"><br>
             <?php if(isset($_SESSION['err_email'])) {
                 echo '<div class="registry_error">'.$_SESSION['err_email'].'</div></br>';
                 unset($_SESSION['err_email']);
             } ?>

             <p>Password:</p> <input type="password" class="form-control col-6" name="password" value="<?php if(isset($_SESSION['password'])) echo $_SESSION['password']; unset($_SESSION['password']); ?>"><br>
             <p>Repeat password:</p> <input type="password" class="form-control col-6" name="password2" value="<?php if(isset($_SESSION['password2'])) echo $_SESSION['password2']; unset($_SESSION['password2']); ?>"><br>
                 <?php if(isset($_SESSION['err_password'])) {
                     echo '<div class="registry_error">'.$_SESSION['err_password'].'</div></br>';
                     unset($_SESSION['err_password']);
                 } ?>

             <p>Name:</p> <input type="text" class="form-control col-6" name="name" value="<?php if(isset($_SESSION['name'])) echo $_SESSION['name']; unset($_SESSION['name']); ?>"><br>
                 <?php if(isset($_SESSION['err_name'])) {
                     echo '<div class="registry_error">'.$_SESSION['err_name'].'</div></br>';
                     unset($_SESSION['err_name']);
                 } ?>

             <p>Location:</p> <input type="text" class="form-control col-6" name="location" value="<?php if(isset($_SESSION['location'])) echo $_SESSION['location']; unset($_SESSION['location']); ?>"><br>
                 <?php if(isset($_SESSION['err_location'])) {
                     echo '<div class="registry_error">'.$_SESSION['err_location'].'</div></br>';
                     unset($_SESSION['err_location']);
                 } ?>

             <p class="">Birth date:</p>
             <div class="row">
                 <select class="ml-3 form-control col-2" name="month">
                     <option value="1">Jan</option>
                     <option value="2">Feb</option>
                     <option value="3">Mar</option>
                     <option value="4">Apr</option>
                     <option value="5">May</option>
                     <option value="6">Jun</option>
                     <option value="7">Jul</option>
                     <option value="8">Aug</option>
                     <option value="9">Sep</option>
                     <option value="10">Oct</option>
                     <option value="11">Nov</option>
                     <option value="12">Dec</option>
                 </select>
                 <select class="ml-3 form-control col-2" name="day">
                     <?php for ($i=1; $i < 32; $i++) {
                         echo "<option value='{$i}'>{$i}</option>";
                     } ?>
                 </select>
                 <select class="ml-3 form-control col-2" name="year">
                     <?php for ($i=2020; $i >= 1900; $i--) {
                         echo "<option value='{$i}'>{$i}</option>";
                     } ?>
                 </select>
             </div><br>
                 <?php if(isset($_SESSION['err_birth_date'])) {
                     echo '<div class="registry_error">'.$_SESSION['err_birth_date'].'</div></br>';
                     unset($_SESSION['err_birth_date']);
                 } ?>

             <label for="sex">Sex:</label>
             <select class="form-control col-4" id="sex" name="sex">
                 <option value="1" <?php if(isset($_SESSION['sex']) and $_SESSION['sex'] == "1") echo "selected"; ?>>Man</option>
                 <option value="2" <?php if(isset($_SESSION['sex']) and $_SESSION['sex'] == "2") echo "selected"; ?>>Woman</option>
             </select> <br>
             <label for="looking_for">Looking for:</label>
             <select class="form-control col-4" id="looking_for" name="looking_for">
                 <option value="1" <?php if(isset($_SESSION['looking_for']) and $_SESSION['looking_for'] == "1") echo "selected"; ?>>Man</option>
                 <option value="2" <?php if(isset($_SESSION['looking_for']) and $_SESSION['looking_for'] == "2") echo "selected"; ?>>Woman</option>
             </select><br>
             <p class="">Aged:</p>
             <div class="row">
                 <input type="number" min="18" class="form-control col-2 ml-3 mr-3" name="age_min" value="<?php if(isset($_SESSION['age_min'])) echo $_SESSION['age_min']; unset($_SESSION['age_min']); ?>">to
                 <input type="number" max="120" class="form-control col-2 ml-3" name="age_max" value="<?php if(isset($_SESSION['age_max'])) echo $_SESSION['age_max']; unset($_SESSION['age_max']); ?>"><br>
             </div><br>
                 <?php if(isset($_SESSION['err_age'])) {
                     echo '<div class="registry_error">'.$_SESSION['err_age'].'</div></br>';
                     unset($_SESSION['err_age']);
                 } ?>


             <div class="row">

                 <input type="submit" class="btn btn-danger btn-lg mt-4" name="register" value="Register"> <div class="ml-5 pt-3"> <?php if(isset($message))echo $message; ?></div>
             </div>
         </form>
     </div>
 </main>

</div>


<?php //require_once "includes/footer.php" ?>
