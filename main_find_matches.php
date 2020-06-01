<?php require_once "includes/header.php" ?>
<div class="container">

    <main>
        <div class="row left-side-bar">
            <div class="col-4">
                <div class="row">
                    <div id="my-profile-bar" class="col-12">
                        <a href="edit_profile_1.php" class="mb-1 mr-5"><img src="https://via.placeholder.com/40" class="rounded-circle mr-3 mb-1" alt="">My Profile</a>
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

            <?php

            $user = User::findById($_SESSION['user_id']);

            $possible_matches = $user->findPossibleMatches(100);                     // array of possible matches for $user

            if(!empty($possible_matches))
                $possible_match = $possible_matches[0];                       // take the first match

            // print_r($possible_match);
            if (!empty($possible_match))
                $photos_of_pm = Photo::findAllOfOneUser($possible_match->id);

            ?>

            <!-- RIGHT SIDE PANEL - PROPOSTIONS TO MATCH -->

            <div class="col-8">
                <div class="row right-side-bar">
                    <div id="propositions">
                        <!-- <div class="propositions-img-holder" style="position: relative;"> -->
                        <?php if (!empty($possible_match)) {

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
                                        <!-- <a href="action/make_rejection.php?rejected_user_id=<?php if (isset($possible_match->id)) echo $possible_match->id ?>"> -->
                                        <img src="images/cross.png" class="" alt="">
                                        <!-- </a> -->
                                    </div>
                                </div>
                                <div class="icon-prop col-6">
                                    <div class="icon-prop-inner rounded-circle">
                                        <!-- <a href="action/make_request.php?requested_user_id=<?php if (isset($possible_match->id)) echo $possible_match->id ?>"> -->
                                        <img src="images/check2.png" class="" alt="">
                                        <!-- </a> -->
                                    </div>
                                </div>
                            </div>
                        <?php } else {
                            echo "<div class='no-matches-info'>No possible matches for this moment. You might want to change your searching criteria.</div>";
                        } ?>
                    </div>
                </div>
            </div>

        </div>
    </main>

</div>

<script>
    <?php
        $possible_matches_ids = [];
        foreach ($possible_matches as $pm) {
            array_push($possible_matches_ids, $pm->id);
        }
    ?>
    
    let possible_matches = JSON.parse('<?php echo json_encode($possible_matches_ids) ?>'); // convert php objects into json objects

    function updateProposition() {
        // if (possible_matches.length <= 1)                   // resfresh site from time to time to find new matches
        //     location.reload();
        $.ajax({
            type: 'POST',
            url: 'action/update_proposition.php',
            data: {
                objects_array: possible_matches
            },
            success: function(output) {
                document.getElementById("propositions").innerHTML = output;
                if (document.getElementsByClassName("icon-prop-inner")[0])
                    document.getElementsByClassName("icon-prop-inner")[0].addEventListener("click", makeRejection);
                if (document.getElementsByClassName("icon-prop-inner")[1])
                    document.getElementsByClassName("icon-prop-inner")[1].addEventListener("click", makeRequest);
            }
        });
    }

    function makeRejection() {
        $.ajax({
            type: 'GET',
            url: 'action/make_rejection.php',
            data: 'rejected_user_id=' + possible_matches[0],
            success: function() {
                console.log("user rejected");
                possible_matches.shift();
                updateProposition();
            }
        });
    }

    function makeRequest() {
        $.ajax({
            type: 'GET',
            url: 'action/make_request.php',
            data: 'requested_user_id=' + possible_matches[0],
            success: function() {
                console.log("user requested");
                possible_matches.shift();
                updateProposition();
            }
        });
    }
    if (document.getElementsByClassName("icon-prop-inner")[0])
        document.getElementsByClassName("icon-prop-inner")[0].addEventListener("click", makeRejection);
    if (document.getElementsByClassName("icon-prop-inner")[1])
        document.getElementsByClassName("icon-prop-inner")[1].addEventListener("click", makeRequest);
</script>

<?php require_once "includes/footer.php" ?>