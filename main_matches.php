<?php require_once "includes/header.php" ?>

<?php

$user = User::findById($_SESSION['user_id']);
$matches = $user->findMatches();

if (isset($_GET['user_id'])) {
    $match_profile = User::findById($_GET['user_id']);
} else {
    if ($matches[0]->first_user_id != $user->id) {
        $match_profile = User::findById($matches[0]->first_user_id);
    } else {
        $match_profile = User::findById($matches[0]->second_user_id);
    }
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

            <?php

            $match_profile_photos = Photo::findAllOfOneUser($match_profile->id);

            ?>

            <div class="col-8">
                <div class="row right-side-bar">
                    <div id="show-profile">
                        <!-- <div class="propositions-img-holder" style="position: relative;"> -->
                        <div id="carouselExampleIndicators" class="carousel slide show-profile-img-holder" style="position: relative;" data-ride="carousel">
                            <ol class="carousel-indicators">
                                <?php
                                $counterOfPhotos = count($match_profile_photos);
                                for ($i = 0; $i < $counterOfPhotos; $i++) {
                                    echo "<li data-target='#carouselExampleIndicators' data-slide-to='$i'></li>";
                                }
                                ?>
                            </ol>
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <img class="d-block w-100" src="<?php
                                                                    if (isset($match_profile->profile_photo_id) and $match_profile->profile_photo_id != 0) {
                                                                        $photo_id = $match_profile->profile_photo_id;
                                                                        $photo = Photo::findById($photo_id);
                                                                        $src = $photo->filename;
                                                                        echo "users_images/{$src}";
                                                                    } else {
                                                                        if ($match_profile->sex == 1)
                                                                            echo 'images/profile_pic_m.png';
                                                                        else
                                                                            echo 'images/profile_pic_w.png';
                                                                    } ?>" alt="1 slide">
                                </div>
                                <?php

                                for ($i = 1; $i < $counterOfPhotos; $i++) {

                                ?>
                                    <div class="carousel-item">
                                        <img class="d-block w-100" src="users_images/<?php echo $match_profile_photos[$i]->filename ?>" alt="<?php echo $i + 1 ?> slide">
                                    </div>
                                <?php
                                }

                                ?>

                            </div>
                            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </a>
                            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </a>
                            <!-- <div class="name">
                                     </div> -->
                        </div>

                        <h1 class="mt-3"><?php if (isset($match_profile->name)) echo $match_profile->name . ", ";
                                            if (isset($match_profile->birth_date)) echo $match_profile->getAge($match_profile->birth_date); ?></h1>
                        <div class="pt-2 profile-caption">
                            <?php if (isset($match_profile->caption)) echo substr($match_profile->caption, 0, 150); ?> <br>
                        </div>
                        <div class="row">
                            <a href="messages.php?user_id=<?php echo $match_profile->id ?>" id="send_message" class="btn btn-primary col-8">Message</a>
                            <div class="col-2"></div>
                            <a href="action/unmatch.php?user_id=<?php echo $match_profile->id ?>" id="unmatch" class="btn btn-danger col-2 float-right">Unmatch</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>

</div>

<?php require_once "includes/footer.php" ?>