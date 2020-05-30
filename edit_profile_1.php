<?php require_once "includes/header.php" ?>
<?php

$user = User::findById($_SESSION['user_id']);

$lf_aged = explode("-", $user->looking_for_aged);

if (isset($_POST['add_photo']) and !empty($_FILES['file_upload']['name'])) {         // adding a photo

    // print_r($_FILES);

    $new_photo = new Photo();
    $new_photo->createFile($_FILES['file_upload']);

    $new_photo->user_id = $_SESSION['user_id'];

    if (!$new_photo->save()) {
        $photo_error = join("<br>", $new_photo->errors);        // eroory z tabeli, które wyświetlimy niżej
    } else {
        header('Refresh: 0');
    }
}

if (isset($_POST['change'])) {

    if ($_POST['age_min'] > $_POST['age_max']) {
        $temp = $_POST['age_max'];
        $_POST['age_max'] = $_POST['age_min'];
        $_POST['age_min'] = $temp;
    }

    $user->caption = $_POST['caption'];
    $user->looking_for = $_POST['looking_for'];
    $user->looking_for_aged = $_POST['age_min'] . "-" . $_POST['age_max'];

    $user->save();

    header('Refresh: 0');
}

?>

<div class="container">

    <main>
        <div class="row left-side-bar">
            <div class="col-4">
                <div class="row">
                    <div id="my-profile-bar" class="col-12">
                        <a href="main_find_matches.php" class="mb-1 mr-5"><img src="https://via.placeholder.com/40" class="rounded-circle mr-3 mb-1" alt="">Explore</a>
                        <a href="logout.php" title="Logout" class="ml-5"><img src="images/logout.png" alt=""> </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6 mm-bar pt-2">
                        <a href="main_matches.php">Matches</a>
                    </div>
                    <div class="col-6 mm-bar pt-2">
                        <a href="messages.php">Messages</a>
                    </div>
                </div>
                <div class="row">
                    <div id="matches-messages" class="col-12">

                        <?php

                        $user = User::findById($_SESSION['user_id']);
                        $matches = $user->findMatches();

                        foreach ($matches as $singleMatch) {
                            if ($singleMatch->first_user_id != $user->id) {
                                $match = User::findById($singleMatch->first_user_id);
                            } else {
                                $match = User::findById($singleMatch->second_user_id);
                            }

                            $match_profile_photo = $match->getProfilePhoto();

                        ?>
                            <div class="row ">
                                <div class="match-row col-12">
                                    <img src="users_images/<?php echo $match_profile_photo->filename; ?>" class="img-fluid matches-row-img" alt="">
                                    <a href="main_matches.php?user_id=<?php echo $match->id; ?>" class="ml-3"><?php echo $match->name . ", " . User::getAge($match->birth_date); ?></a>
                                    <a href="messages.php?user_id=<?php echo $match->id ?>" class="float-right mt-4"><img src="images/message_icon.png" alt="Send a message" title="Send a message"></a>
                                </div>
                            </div>
                        <?php }       // end of for loop (matches) 
                        ?>
                    </div>
                </div>
            </div>

            <div class="col-8 right-side-bar">
                <div class="row">
                    <div id="profile_edit">
                        <div class="profile-edit-switch">

                            <a href="">Edit your personal data</a>
                        </div>

                        <form class="form-group pl-5 pt-5" method="post" enctype="multipart/form-data">

                            <p>Your photos:</p>

                            <div class="row">
                                <?php

                                $photos = Photo::findAllOfOneUser($user->id);

                                // print_r($photos);

                                for ($i = 0; $i < 3; $i++) {         // take one photo at a time

                                    if (isset($photos[$i]))
                                        $src = $photos[$i]->filename;
                                ?>
                                    <div class="col-4 user-photos" style="width:175px !important; height:200px;">
                                        <img class="img-fluid" onclick="del_photo(<?php if (isset($photos[$i])) echo $photos[$i]->id; ?>)" src="<?php if (isset($photos[$i])) echo "users_images/$src";
                                                                                                                                                else echo "https://via.placeholder.com/175x200" ?>" alt="">
                                    </div>
                                    <?php
                                }

                                for ($i = 3; $i < 6; $i++) {         // take one photo at a time

                                    if (isset($photos[$i])) {
                                        $src = $photos[$i]->filename;
                                    ?>
                                        <div class="col-4 user-photos" style="width:175px; height:200px;">
                                            <img class="img-fluid" onclick="del_photo(<?php if (isset($photos[$i])) echo $photos[$i]->id; ?>)" src="<?php if (isset($photos[$i])) echo "users_images/$src";
                                                                                                                                                    else echo "https://via.placeholder.com/175x200" ?>" alt="">
                                        </div>
                                    <?php
                                    } else {
                                    ?>
                                        <div class="col-4 user-photos" style="width:175px; height:200px;">
                                            <img class="img-fluid" onclick="add_new_photo()" src="https://via.placeholder.com/175x200" alt="">
                                        </div>
                                <?php
                                    }
                                }

                                ?>
                            </div>

                            <br>
                            <p>Add another photo:</p>
                            <input type="file" id="add_photo" class="" name="file_upload">
                            <input type="submit" class="btn btn-danger btn-lg mt-2" name="add_photo" value="Add photo">
                        </form>
                        <?php if (isset($photo_error)) {
                            echo '<div class="registry_error ml-5">' . $photo_error . '</div></br>';
                            unset($photo_error);
                        }
                        ?>
                        <form class="form-group pl-5" method="post" enctype="multipart/form-data">

                            <p>Caption:</p> <textarea type="text" class="form-control" rows="4" name="caption" placeholder="Tell people something about you"><?php if (isset($user->caption)) echo $user->caption;
                                                                                                                                                                unset($user->caption); ?></textarea><br>
                            <?php if (isset($_SESSION['err_caption'])) {
                                echo '<div class="registry_error">' . $_SESSION['err_caption'] . '</div></br>';
                                unset($_SESSION['err_caption']);
                            } ?>


                            <label for="looking_for">Looking for:</label>
                            <select class="form-control col-4" id="looking_for" name="looking_for">
                                <option value="2" <?php if (isset($user->looking_for) and $user->looking_for == "2") echo "selected"; ?>>Men</option>
                                <option value="1" <?php if (isset($user->looking_for) and $user->looking_for == "1") echo "selected"; ?>>Women</option>
                                <option value="3" <?php if (isset($user->looking_for) and $user->looking_for == "3") echo "selected"; ?>>Both</option>
                            </select><br>
                            <p class="">Aged:</p>
                            <div class="row">
                                <input type="number" min="18" class="form-control col-2 ml-3 mr-3" name="age_min" value="<?php if (isset($lf_aged[0])) echo $lf_aged[0];
                                                                                                                            unset($lf_aged[0]); ?>">to
                                <input type="number" min="18" max="120" class="form-control col-2 ml-3" name="age_max" value="<?php if (isset($lf_aged[1])) echo $lf_aged[1];
                                                                                                                                unset($lf_aged[1]); ?>"><br>
                            </div><br>
                            <?php if (isset($_SESSION['err_age'])) {
                                echo '<div class="registry_error">' . $_SESSION['err_age'] . '</div></br>';
                                unset($_SESSION['err_age']);
                            } ?>


                            <div class="row">

                                <input type="submit" class="btn btn-danger btn-lg mt-4" name="change" value="Change">
                                <div class="ml-5 pt-3"></div>
                            </div>
                    </div>
                </div>
            </div>

        </div>
    </main>

</div>

<script type="text/javascript">
    function add_new_photo() {

        let button = document.getElementById("add_photo");

        console.log(button);

        button.click();
    }

    function del_photo(id) {

        if (!isNaN(id)) {

            let x = confirm("Delete the photo?");

            let link = "action/delete_photo.php?photo_id=" + id;

            if (x == true) {
                window.location.replace(link);
            }

        }
    }
</script>

<?php // require_once "includes/footer.php" 
?>