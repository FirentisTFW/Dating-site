<?php

    require_once "../init.php";

    if (isset($_GET['match_id'])) {
        
        $match_profile = User::findById($_GET['match_id']);
        $match_profile_photos = Photo::findAllOfOneUser($match_profile->id);


    ?>

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

    <?php
}

?>