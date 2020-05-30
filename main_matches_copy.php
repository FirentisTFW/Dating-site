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
                             <a href="">Messages</a>
                         </div>
                     </div>
                     <div class="row">
                         <div id="matches-messages" class="col-12">
                             adasdsa
                         </div>
                     </div>
                 </div>

                 <?php

                    $user = User::findById($_SESSION['user_id']);

                    $possible_match = $user->findPossibleMatches();

                    // print_r($possible_match);

                  ?>

                 <div class="col-8">
                     <div class="row right-side-bar">
                         <div id="propositions">
                             <div class="propositions-img-holder" style="position: relative;">
                                 <!-- <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                                     <ol class="carousel-indicators">
                                         <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                                         <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                                         <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                                     </ol>
                                     <div class="carousel-inner">
                                         <div class="carousel-item active">
                                             <img class="d-block w-100" src="https://source.unsplash.com/collection/190727/1400x700" alt="First slide">
                                         </div>
                                         <div class="carousel-item">
                                             <img class="d-block w-100" src="https://source.unsplash.com/collection/190727/1400x700" alt="Second slide">
                                         </div>
                                         <div class="carousel-item">
                                             <img class="d-block w-100" src="https://source.unsplash.com/collection/190727/1400x700" alt="Third slide">
                                         </div>
                                     </div>
                                     <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                                         <span class="carousel-control-prev-icon"></span>
                                     </a>
                                     <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                                         <span class="carousel-control-next-icon"></span>
                                     </a>
                                 </div> -->

                                 <img src="<?php
                                     if(isset($possible_match->profile_photo_id) and $possible_match->profile_photo_id != 0) {
                                         $photo_id = $possible_match->profile_photo_id;
                                         $photo = Photo::findById($photo_id);
                                         $src = $photo->filename;
                                         echo "users_images/{$src}";

                                     }
                                     else {
                                         if($possible_match->sex == 1)
                                            echo 'images/profile_pic_m.png';
                                         else
                                             echo 'images/profile_pic_w.png';
                                      }?>" class="propositions-img test"alt="">
                                 <div class="name">
                                     <?php if(isset($possible_match->name)) echo $possible_match->name . ", ";  if(isset($possible_match->birth_date)) echo $possible_match->getAge($possible_match->birth_date);; ?>
                                 </div>
                             </div>
                             <div class="pt-2 profile-caption">
                                 <?php if(isset($possible_match->caption)) echo substr($possible_match->caption, 0, 150); ?> <br>
                                 <a href="#">MORE</a>
                             </div>
                             <div class="row no-gutters">
                                 <div class="icon-prop col-6">
                                     <div class="icon-prop-inner rounded-circle">
                                         <a href="action/make_rejection.php?rejected_user_id=<?php if(isset($possible_match->id)) echo $possible_match->id ?>">
                                             <img src="images/cross.png" class="" alt="">
                                         </a>
                                     </div>
                                 </div>
                                 <div class="icon-prop col-6">
                                     <div class="icon-prop-inner rounded-circle">
                                        <a href="action/make_request.php?requested_user_id=<?php if(isset($possible_match->id)) echo $possible_match->id ?>">
                                            <img src="images/check2.png" class="" alt="">
                                        </a>
                                     </div>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>

             </div>
         </main>

         </div>

         <?php  require_once "includes/footer.php" ?>
