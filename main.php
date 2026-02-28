<?php require "inc/session.php";
include 'class/Comment.class.php';
// include 'class/Comment.class.php'
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Network Home</title>
    <link rel="stylesheet" href="css/main-page.css">

</head>

<body>
    <div class="wrapper">
        <?php require 'inc/navbar.php' ?>

        <main class="container">
            <aside class="sidebar">
                <div class="profile">
                    <img src="profile-pic.jpg" alt="Profile Picture" class="profile-pic">
                    <h2>Name Surname</h2>
                    <p>@<?php echo htmlspecialchars($username); ?></p>
                    <button onclick="redirectTo('profile.php')">View Profile</button>
                </div>
                <div class="friends">
                    <h3>Suggested users</h3>
                    <ul>
                        <?php
                        // include 'engine/sql.php';

                        // Initialize an empty string to hold the table rows
                        $listItems = '';
                        $message = '';

                        // Fetch users from the database
                        try {
                            $stmt = $pdo->prepare("SELECT IDAccount1, IDAccount2 FROM Friends WHERE IDAccount2 = :userId");
                            $stmt->bindParam(':userId', $userid, PDO::PARAM_INT);
                            $stmt->execute();
                            $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            $stmt = $pdo->prepare("SELECT IDAccount1, IDAccount2 FROM Friends WHERE IDAccount1 = :userId");
                            $stmt->bindParam(':userId', $userid, PDO::PARAM_INT);
                            $stmt->execute();
                            $demands = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            $stmt = $pdo->prepare("SELECT ID, Username FROM User WHERE ID != :userId");
                            $stmt->bindParam(':userId', $userid, PDO::PARAM_INT);
                            $stmt->execute();
                            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            $AllRequestsToLoggedUser = [];
                            foreach ($requests as $request) {
                                $AllRequestsToLoggedUser[] = $request['IDAccount1'];
                            }
                            $AllDemandsFromLoggedUser = [];
                            foreach ($demands as $demand) {
                                $AllDemandsFromLoggedUser[] = $demand['IDAccount2'];
                            }
                            $AllUsers = [];
                            foreach ($users as $user) {
                                $AllUsers[] = $user['ID'];
                            }

                            $RequestsNotAccepted = array_diff($AllRequestsToLoggedUser, $AllDemandsFromLoggedUser);
                            $DemandsNotAccepted = array_diff($AllDemandsFromLoggedUser, $AllRequestsToLoggedUser);
                            $AllRequestsAndAllDemands = array_merge($AllRequestsToLoggedUser, $AllDemandsFromLoggedUser);
                            $AllRequestsAndAllDemandsWithCount = array_count_values($AllRequestsAndAllDemands); // count can be 1 or 2
                            $FriendsWithCount = array_filter($AllRequestsAndAllDemandsWithCount, function ($count) {
                                return $count > 1;
                            });
                            $Friends = array_keys($FriendsWithCount);
                            $UsersWithRelation = array_merge($Friends, $RequestsNotAccepted, $DemandsNotAccepted);

                            $notAdded = array_diff($AllUsers, $UsersWithRelation);

                            $relations = [];
                            foreach ($RequestsNotAccepted as $re) {
                                $relations[] = ['id' => $re, 'type' => 1];
                            }
                            foreach ($DemandsNotAccepted as $de) {
                                $relations[] = ['id' => $de, 'type' => 2];;
                            }
                            foreach ($Friends as $f) {
                                $relations[] = ['id' => $f, 'type' => 3];;
                            }
                            foreach ($notAdded as $not) {
                                $relations[] = ['id' => $not, 'type' => 4];
                            }

                            // Add new key 'status' with value 'active' to each element
                            foreach ($relations as &$relation) {
                                $stmt = $pdo->prepare("SELECT ID, Username FROM User WHERE ID != :userId AND ID = :id");
                                $stmt->bindParam(':userId', $userid, PDO::PARAM_INT);
                                $stmt->bindParam(':id', $relation['id'], PDO::PARAM_INT);
                                $stmt->execute();

                                // Fetch a single result if expected to have one result per ID
                                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                                if ($user) {
                                    // If a user is found, update the 'name' field
                                    $relation['name'] = $user['Username'];
                                } else {
                                    // Handle case where no user is found
                                    $relation['name'] = 'Not Found'; // or any default value
                                }
                            }
                            unset($relation); // Break the reference to the last element


                            // Print the updated array
                            // print_r($relations);



                            // Check if there are any users
                            if (count($relations) > 0) {
                                // Generate the HTML table rows
                                foreach ($relations as $relation) {
                                    $id = $relation['id'];
                                    $username = $relation['name'];
                                    $listItems .= "<tr><td>$username</td>";
                                    if ($relation['type'] == 1) {
                                        $listItems .= "<td><button class=\"button-recieved\" onclick=\"ManageRequest(1,$userid,$id)\">Accept request</button></td>";
                                    }
                                    if ($relation['type'] == 2) {
                                        $listItems .= "<td><button class=\"button-sent\" onclick=\"\">Request sent</button></td>";
                                    }
                                    if ($relation['type'] == 3) {
                                        $listItems .= "<td><button class=\"button-friend\">Friends :)</button></td>";
                                    }
                                    if ($relation['type'] == 4) {
                                        $listItems .= "<td><button class=\"button-add\" id='$userid$id' onclick=\"addFriend($userid, $id)\">+ Add</button></td>";
                                    }

                                    $listItems .=    "</tr>\n";
                                }
                            } else {
                                // Set the message if there are no users
                                $message = "<tr><td colspan=\"2\">Currently no users to show</td></tr>";
                            }
                        } catch (PDOException $e) {
                            $message = "<tr><td colspan=\"2\">Error: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
                        }
                        ?>
                        <table class="hidden-table">
                            <!-- <thead> -->
                            <!-- <tr>
                                    <th>Username</th>
                                    <th>Action</th>
                                </tr> -->
                            <!-- </thead> -->
                            <tbody>
                                <?php echo $message; ?>
                                <?php echo $listItems; ?>
                            </tbody>
                        </table>
                    </ul>
                </div>
            </aside>

            <section class="feed">

                <?php
                try {
                    $stmt = $pdo->query("SELECT Post.ID, User.Username, IDUser, Title, Description, Image, Likes FROM Post JOIN User on Post.IDUser = User.ID ORDER BY ID desc");
                    // Fetch all results
                    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                } catch (PDOException $e) {
                    echo "Error: " . $e->getMessage();
                    exit();
                }
                ?>
                <div class="post">
                    <h3>Create new post.</h3>
                    <p>Click on create post button to create new post. <a href="#">More info</a></p>
                    <div class="post-actions">
                        <!-- <form action="create_post.php" method="post"> -->
                        <button type="submit" onclick="redirectTo('create_post.php')" id="bn_create_post">Create post </button>
                        <!-- </form> -->
                    </div>
                </div>
                <?php foreach ($rows as $row): ?>
                    <div id='<?= $row['ID'] ?>' class="post">
                        <h3><?php echo htmlspecialchars($row['Username']) . " : " . htmlspecialchars($row['Title']); ?></h3>
                        <p><?php echo htmlspecialchars($row['Description']); ?></p>
                        <?php if ($row['Image']): ?>
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($row['Image']); ?>" style="max-width : 95%" alt="Image" />
                        <?php else: ?>
                            <!-- No Image -->
                        <?php endif; ?>
                        <!-- <td><?php echo htmlspecialchars($row['Likes']); ?></td> -->
                        <div class="post-actions">
                            <button>Like</button>
                            <button onclick="CommentClick(<?= $row['ID'] ?>)">Comment</button>
                            <button>Share</button>
                        </div>
                        <div id="commentinput<?= $row['ID'] ?>"></div>
                        <div id="commentsection<?= $row['ID'] ?>">
                            <?php
                            $comments = array();
                            $comments = Comment::filterByPost($pdo, $row['ID']);
                            // print_r($comments);exit;
                            if (!empty($comments)) {
                                foreach ($comments as $comment) {
                                    echo "<br>".$comment->getUsername()." : " . $comment->getText() . "<br>"; // Assuming getText() is a method to retrieve comment text
                                    // echo "<br>Comment: " . $comment->getText() . "<br>"; // Assuming getText() is a method to retrieve comment text
                                
                                }
                            }
                            // else {
                            //     echo "<br>No comments found for this PostID.";
                            // }
                            // print_r(Comment::filterByPost($pdo, $row['ID']));

                            ?>
                        </div>
                    </div>
                <?php endforeach; ?>

                <!-- <div class="post">
                    <h3>Elon Musk</h3>
                    <p>Check out my new blog post! <a href="#">Read here</a></p>
                    <div class="post-actions">
                        <button>Like</button>
                        <button>Comment</button>
                        <button>Share</button>
                    </div>
                </div>
                <div class="post">
                    <h3>John Doe</h3>
                    <p>Just had a great day at the park! #fun #sunnyday</p>
                    <img src="img/parkday.png" alt="Park Day">
                    <div class="post-actions">
                        <button>Like</button>
                        <button>Comment</button>
                        <button>Share</button>
                    </div>
                </div>


                <div class="post">
                    <h3>Alice Smith</h3>
                    <p>Check out my new blog post! <a href="#">Read here</a></p>
                    <div class="post-actions">
                        <button>Like</button>
                        <button>Comment</button>
                        <button>Share</button>
                    </div>
                </div>
                <div class="post">
                    <h3>Alice Smith</h3>
                    <p>Check out my new blog post! <a href="#">Read here</a></p>
                    <div class="post-actions">
                        <button>Like</button>
                        <button>Comment</button>
                        <button>Share</button>
                    </div>
                </div>
                <div class="post">
                    <h3>Alice Smith</h3>
                    <p>Check out my new blog post! <a href="#">Read here</a></p>
                    <div class="post-actions">
                        <button>Like</button>
                        <button>Comment</button>
                        <button>Share</button>
                    </div>
                </div> -->
            </section>
        </main>

        <?php require 'inc/footer.php' ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- <script src="js/manage_request.js"></script> -->
    <script>
        function CommentClick(id) {
            // Assume `id` is the ID of the element you want to add the input to
            var postDiv = document.getElementById('commentinput' + id);
            var exists = document.getElementById('inputId' + id);
            if (!exists) {
                // Create the container div
                var container = document.createElement('div');
                container.style.position = 'relative';
                container.style.width = '100%'; // Full width of the parent container
                container.style.marginTop = '2vh'; // Margin at the top

                // Create the input element
                var input = document.createElement('input');
                input.setAttribute('type', 'text');
                input.setAttribute('id', 'inputId' + id);
                input.setAttribute('name', 'inputName');
                input.setAttribute('placeholder', 'Type your comment here...');

                // Apply styles to the input
                input.style.width = 'calc(100% - 40px)'; // Adjust width to account for the button
                input.style.padding = '10px';
                input.style.border = '1px solid #ccc';
                input.style.borderRadius = '4px';
                input.style.boxSizing = 'border-box';
                // input.style.marginRight = '1vw';

                // Create the button element
                var button = document.createElement('button');
                button.setAttribute('type', 'button');
                button.id = 'bn_post_comment' + id;
                button.onclick = function() {
                    PostComment(id);
                };
                button.innerHTML = '&#9654;'; // Unicode for a right-pointing triangle (arrow)
                button.style.position = 'absolute';
                button.style.top = '50%';
                button.style.right = '10px'; // Adjust to position button correctly
                button.style.transform = 'translateY(-50%)';
                button.style.background = '#007BFF'; // Button background color
                button.style.color = '#fff'; // Button text color
                button.style.border = 'none';
                button.style.borderRadius = '4px';
                button.style.padding = '10px';
                button.style.cursor = 'pointer';

                // Append the input and button to the container
                container.appendChild(input);
                container.appendChild(button);

                // Append the container to the postDiv
                postDiv.appendChild(container);
            } else {
                postDiv.removeChild(exists.parentNode); // Remove the entire container
            }
        }

        function PostComment(postid) {
            // ($pdo, $id = null, $postID = null, $idAccount = null, $text = null, $timeStamp = null)
            // Define the parameters
            var textElement = document.getElementById('inputId' + postid);
            var textValue = textElement.value;
            const params = {
                postid: postid,
                accountid: <?= $userid ?>,
                text: textValue
            };

            // Make the AJAX request
            $.ajax({
                url: 'ajx/ajxPostComment.php',
                type: 'POST',
                data: params, // Parameters to send with the request
                dataType: 'json', // Expected data type from the server
                success: function(data) {
                    console.log('Success:', data);
                    // console.log(postid);
                    CommentClick(postid);
                    var postDiv = document.getElementById('commentsection' + postid);
                    postDiv.innerHTML += "<br>Comment: " + data + "<br>";

                    // echo "<br>Comment: " . $comment->getText() . "<br>";

                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });

        }

        function addFriend(id1, id2) {
            var idAccount1 = id1;
            var idAccount2 = id2;

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'ajx/ajxSendFriendRequest.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var id = id1.toString() + id2.toString();
                        var btn = document.getElementById(id);
                        if (btn) {
                            btn.className = 'button-sent';
                            btn.textContent = 'Request sent';
                        } else {}
                    } else {
                        alert('Error sending friend request.');
                    }
                }
            };

            xhr.send('idAccount1=' + encodeURIComponent(idAccount1) + '&idAccount2=' + encodeURIComponent(idAccount2));
        }
    </script>
    <script>
        function redirectTo(url) {
            window.location.href = url;
        }
    </script>
</body>

</html>