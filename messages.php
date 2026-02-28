<?php require "inc/session.php"; 
require "class/User.class.php"; 

// require "class/Message.class.php"; 
$userObj = new User($pdo);
// echo $userObj->getFriends($userid)['message']; exit;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages</title>
    <link rel="stylesheet" href="css/messages.css">
    <script src="js/messages.js" defer></script>
</head>
<body>
    <div class="wrapper">
        <?php require 'inc/navbar.php' ?>

        <main class="container">
            <aside class="contact-list">
                <h2>Contacts</h2>

                <?php
                include 'engine/sql.php';
                // Initialize an empty string to hold the list items
                $listItems = $userObj->getFriendList($userid)['listItems'];
                $message = $userObj->getFriendList($userid)['message'];
                ?>
                <ul>
                    <!-- <li><a href="#" data-user="john_doe">John Doe</a></li>
                    <li><a href="#" data-user="alice_smith">Alice Smith</a></li>
                    <li><a href="#" data-user="bob_johnson">Bob Johnson</a></li> -->
                    <?php echo $message ?>
                    <?php echo $listItems; ?>
                </ul>
            </aside>

            <section class="message-area">
                <div class="messages" id="messages">
                    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                    <script>
                        // Initialize variables
                        var userid = <?= $userid ?>;
                        var recipient = 0;

                        $(document).ready(function() {
                            // Function to load messages
                            function loadMessages() {
                                var currentUserId = userid;
                                var recipientId = recipient;
                                $.ajax({
                                    url: 'ajx/ajxLoadMessages.php', // Path to your PHP script
                                    type: 'GET', // Assuming no data needs to be sent
                                    data: {
                                        userId: currentUserId,
                                        recipientId: recipientId
                                    },
                                    dataType: 'json',
                                    success: function(response) {
                                        // Clear existing messages
                                        $('#messages').empty();
                                        if (recipient != 0) {
                                            if (response.error) {
                                                console.error(response.error);
                                                $('#messages').html('<p class="error">An error occurred while loading messages.</p>');
                                            } else if (response.length === 0) {
                                                // Display a message when no messages are available
                                                $('#messages').html('<p class="no-messages">Currently no messages</p>');
                                            } else {
                                                // Append new messages
                                                response.forEach(function(message) {
                                                    var messageHtml = '<div class="message ' + message.class + '">';
                                                    messageHtml += '<p>' + message.text + '</p>';
                                                    messageHtml += '<span class="timestamp">' + message.time + '</span>';
                                                    messageHtml += '</div>';
                                                    $('#messages').append(messageHtml);
                                                });
                                            }
                                        } else {
                                            $('#messages').html('<p class="no-messages">Select recipient on the left :)</p>');
                                        }

                                    },
                                    error: function(xhr, status, error) {
                                        console.error('AJAX Error: ' + status + ' ' + error);
                                        $('#messages').html('<p class="error">An error occurred while loading messages.</p>');
                                    }
                                });
                            }

                            // Load messages when the page is ready
                            loadMessages();

                            // Optionally, refresh messages periodically
                            setInterval(loadMessages, 5000); // Refresh every 5 seconds

                            // Function to change the recipient
                            window.ChangeRecipient = function(id) {
                                console.log('Recipient Changed : ' + id);
                                recipient = id;
                                // Optionally call another function if needed
                                // insertText(userid, recipient, document.getElementById('message').value);
                                loadMessages();
                            };
                        });
                    </script>

                </div>
                <div class="message-input">
                    <input type="text" id="message" placeholder="Type a message...">
                    <button id="send">Send</button>
                </div>
            </section>
        </main>

        <?php require 'inc/footer.php' ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function insertText(idAccount1, idAccount2, text) {
            $.ajax({
                url: 'ajx/sendMessage.php', // Path to your server-side script
                type: 'POST',
                data: {
                    idAccount1: idAccount1,
                    idAccount2: idAccount2,
                    text: text
                },
                success: function(response) {
                    // Handle success - you might want to update the UI or notify the user
                    // alert(response);
                },
                error: function(xhr, status, error) {
                    // Handle error
                    console.error('AJAX Error: ' + status + error);
                }
            });
        }
    </script>
</body>

</html>