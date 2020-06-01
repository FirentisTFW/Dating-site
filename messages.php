<?php require_once "includes/header.php" ?>

<?php

$user = User::findById($_SESSION['user_id']);
$conversations = $user->findConversations();

if (isset($_GET['user_id'])) {
    $message_profile = User::findById($_GET['user_id']);
} else {              
    if(isset($conversations) and !empty($conversations)) {
        if ($conversations[0]->first_user_id != $user->id) {                     // if user clicked "Messages", not any specific user -> redirect to the first match
            $message_profile = User::findById($conversations[0]->first_user_id);
        } else {
            $message_profile = User::findById($conversations[0]->second_user_id);
        }
    }                                                                 
}

// $conversation_exists = Conversation::findByQuery("SELECT * FROM conversations WHERE (first_user_id = 16 AND second_user_id = {$_SESSION['user_id']})
//     OR (first_user_id = {$_SESSION['user_id']} AND second_user_id = 16) LIMIT 1");
//
//     print_r($conversation_exists);


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

                        foreach($conversations as $conversation) {

                            if ($conversation->first_user_id != $user->id) 
                                $match = User::findById($conversation->first_user_id);
                            else
                                $match = User::findById($conversation->second_user_id);

                            $message_profile_photo = $match->getProfilePhoto();

                        ?>
                            <div class="row ">
                                <div class="match-row col-12">
                                    <img src="users_images/<?php echo $message_profile_photo->filename; ?>" class="img-fluid matches-row-img" alt="">
                                    <a href="messages.php?user_id=<?php echo $match->id; ?>" class="ml-3"><?php echo $match->name . ", " . User::getAge($match->birth_date); ?></a>
                                </div>
                            </div>
                        <?php }       // end of for loop (conversations) 
                        ?>
                    </div>
                </div>
            </div>


            <div class="col-8">
                <div class="row right-side-bar">
                    <?php if(isset($message_profile_photo)) { ?>
                    <div id="show-messages">
                        <div class="messages-list">
                            <div class="messages-user-info">
                                <div class="messages-user-info-img">
                                    <?php
                                    $message_profile_photo = $message_profile->getProfilePhoto();
                                    echo "<a href='main_matches.php?user_id=$message_profile->id'><img src='users_images/$message_profile_photo->filename' class='img-fluid' style='width: 60px; height: 60px;'></a>";
                                    ?>
                                </div>
                                <?php
                                echo "<a href='main_matches.php?user_id=$message_profile->id'>$message_profile->name</a>" . ", "
                                    . User::getAge($message_profile->birth_date) . "<p>" . $message_profile->location . "</p>";
                                ?>
                            </div>

                            <!-- Display messages -->
                            <div class="messages-messages-field">
                                <?php

                                $conversation = Conversation::findByQuery("SELECT * FROM conversations WHERE (first_user_id = {$message_profile->id} AND second_user_id = {$_SESSION['user_id']})
                                            OR (first_user_id = {$_SESSION['user_id']} AND second_user_id = {$message_profile->id}) LIMIT 1");

                                $conversation = array_shift($conversation);


                                if (!empty($conversation)) {                                        // converastion exists
                                    $messages = $conversation->findMessagesInConversation(40);      // load 40 messages automatically
                                    $loadMoreButton = $conversation->areThereMoreMessages($messages[0]->id);

                                    if ($loadMoreButton) {                                          // there are more messages in this conversation, create "load more" button
                                        echo "<div id='load-more-messages'>Load more messages</div>";
                                    }

                                    echo "<div class='messages-only-messages-field'>";
                                    $conversation->displayMessages($messages);                      // display messages in conversation
                                }

                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="write-message">
                        <!-- <iframe name="frame" style="display: none"></iframe> -->
                        <form class="" method="post" target="frame">
                            <div class="row mt-4">
                                <div class="write-message-content col-10 ml-4">
                                    <textarea class="form-control" id="message_content" name="message_content" rows="2" cols="35"></textarea>
                                </div>
                                <img class="rounded-circle ml-2" style="cursor: pointer;" id="send_message" src="images/send_message2.png" alt="Send message">
                            </div>
                        </form>
                        
                    </div>
                    <?php } 
                    else {
                        echo "<div id='propositions'> <div class='no-matches-info'>You didn't start any conversation yet.</div> </div>";
                    }?>
                </div>
            </div>
        </div>

</div>
</main>

</div>

<script type="text/javascript">

    <?php if(isset($message_profile)) { ?>

    window.addEventListener('DOMContentLoaded', (event) => {

        lastMessageId = <?php if(isset($conversation)) echo $conversation->getLastMessageId(); ?> // id of the last message (updated after ajax request in function updateLastMessageId())
        firstMessageId = <?php if (isset($messages)) echo $messages[0]->id ?> // id of the oldest displayed message

        document.querySelector(".messages-list").scrollTo(0, document.querySelector(".messages-list").scrollHeight);

        function sendMessage() { // using ajax script to send message without reloading the page

            let message = document.getElementById("message_content").value;

            $.ajax({
                type: 'POST',
                url: 'action/send_message.php',
                data: {
                    content: message,
                    target_id: "<?php echo $message_profile->id; ?>"
                },
                success: function(msg) {
                    if(!lastMessageId) location.reload();                               // this is the first message in conversation -> reload the page to get needed variables
                    document.getElementById("message_content").value = "";
                    setTimeout(function() {
                        document.querySelector(".messages-list").scrollTo(0, document.querySelector(".messages-list").scrollHeight);
                    }, 510);
                }
            });
        }

        function displayMessages(shouldYouDisplayOlder = false) {
            $.ajax({
                type: 'POST',
                url: 'action/display_messages.php',
                data: {
                    last_message_id: lastMessageId,
                    first_message_id: firstMessageId,
                    conversation_id: "<?php if(isset($conversation))echo $conversation->id; ?>",
                    older: shouldYouDisplayOlder
                },
                success: function(newMessages) {
                    let messagesField = document.getElementsByClassName("messages-only-messages-field")[0];
                    if (!shouldYouDisplayOlder) {
                        messagesField.innerHTML = messagesField.innerHTML + newMessages;
                        updateLastMessageId();
                    }
                    else {
                        messagesField.innerHTML = newMessages + messagesField.innerHTML;
                        updateFirstMessageId();
                    }
                }
            });
        }

        function updateLastMessageId() {
            $.ajax({
                type: 'POST',
                url: 'action/update_last_message_id.php',
                data: {
                    conversation_id: "<?php if (isset($conversation)) echo $conversation->id; ?>"
                },
                success: function(id) {
                    id = id.trim();
                    lastMessageId = id;
                }
            });
        }

        function updateFirstMessageId() {
            tempId = document.getElementsByClassName("messages-only-messages-field")[0].getElementsByClassName("messages-single-message")[0].id; // get the first div with message
            firstMessageId = Number(tempId.substr(10));
        }

        // function changePersonMessages() {
        //     $.ajax({
        //         type:'GET',
        //         url: 'change_person_messages.php',
        //         data: '',
        //         success: function() {

        //         }
        //     });
        // }

        let displayingMessages = setInterval(displayMessages, 500);

        document.getElementById("send_message").addEventListener("click", sendMessage);

        document.getElementById('message_content').onkeydown = function(pressedKey) { // if users press enter typing message, send message
            if (pressedKey.keyCode == 13) {
                sendMessage();
            }
        }

        if (document.getElementById("load-more-messages")) { // there are more messages to load
            document.getElementById("load-more-messages").addEventListener("click", function() {
                displayMessages(true);
            });
        }
    });

<?php } ?>

</script>

<?php require_once "includes/footer.php" ?>