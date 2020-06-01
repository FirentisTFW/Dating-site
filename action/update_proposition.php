<?php

require_once "../init.php";

if (isset($_POST['objects_array'])) {

    $possible_matches = $_POST['objects_array'];

    $possible_match_array = $possible_matches[0];
}

// $possible_match = User::createObject($possible_match_array);            // data is passed as array by POST, data must be converted into object 
if(isset($possible_match_array) and !empty($possible_match_array))
    $possible_match = User::findById($possible_match_array);            // data is passed as array by POST, data must be converted into object 

// print_r($possible_match);

if (!empty($possible_match)) {
    $photos_of_pm = Photo::findAllOfOneUser($possible_match->id);
?>

    <div id="carouselExampleIndicators" class="carousel slide propositions-img-holder" style="position: relative;" data-ride="carousel">
        <ol class="carousel-indicators">
            <?php
            $counterOfPhotos = count($photos_of_pm);
            for ($i = 0; $i < $counterOfPhotos; $i++) {
                echo "<li data-target='#carouselExampleIndicators' data-slide-to='$i'></li>";
            }
            ?>
        </ol>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img class="d-block w-100" src="<?php
                                                if (isset($possible_match->profile_photo_id)) {
                                                    if ($possible_match->profile_photo_id != 0) {
                                                        $photo_id = $possible_match->profile_photo_id;
                                                        $photo = Photo::findById($photo_id);
                                                        $src = $photo->filename;
                                                        echo "users_images/{$src}";
                                                    } else {
                                                        if ($possible_match->sex == 1)
                                                            echo 'images/profile_pic_m.png';
                                                        else
                                                            echo 'images/profile_pic_w.png';
                                                    }
                                                } ?>" alt="1 slide">
            </div>
            <?php

            for ($i = 1; $i < $counterOfPhotos; $i++) {

            ?>
                <div class="carousel-item">
                    <img class="d-block w-100" src="users_images/<?php echo $photos_of_pm[$i]->filename ?>" alt="<?php echo $i + 1 ?> slide">
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
        <div class="name">
            <?php if (isset($possible_match->name)) echo $possible_match->name . ", ";                            // show name and age
            if (isset($possible_match->birth_date)) echo $possible_match->getAge($possible_match->birth_date); ?>
        </div>
    </div>

    <div class="pt-2 profile-caption">
        <?php if (isset($possible_match->caption)) echo substr($possible_match->caption, 0, 150); ?> <br>
        <a href="#">MORE</a>
    </div>
    <div class="row no-gutters">
        <div class="icon-prop col-6">
            <div class="icon-prop-inner rounded-circle">
                    <img src="images/cross.png" class="" alt="">
            </div>
        </div>
        <div class="icon-prop col-6">
            <div class="icon-prop-inner rounded-circle">
                    <img src="images/check2.png" class="" alt="">
            </div>
        </div>
    </div>
<?php } else {
    echo "<div class='no-matches-info'>No possible matches for this moment. You might want to change your searching criteria.</div>";
} ?>