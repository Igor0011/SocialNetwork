<?php require "inc/session.php"; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create new post</title>
    <link rel="stylesheet" href="css/profile.css">
</head>

<body>
    <div class="wrapper">
        <?php require 'inc/navbar.php'; ?>
        <main class="container">
            <section class="edit-profile">
                <h2>Create new post</h2>
                <div class="wrapper">
                    <form id="create-post-form" enctype="multipart/form-data">
                        <input type="hidden" name="userid" id="userid" value="<?= $userid ?>">

                        <label for="title">Post title :</label>
                        <input type="text" id="title" name="title" value="Sample Title" required>

                        <label for="description">Post description :</label>
                        <input type="text" id="description" name="description" value="This is a sample description." required>

                        <label for="image">Choose image :</label>
                        <input type="file" id="image" name="image" accept="image/*">

                        <button type="submit">Publish</button>
                    </form>
                </div>
            </section>
        </main>
        <?php require 'inc/footer.php'; ?>
    </div>
</body>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#create-post-form').on('submit', function(e) {
            e.preventDefault(); // Prevent the default form submission

            var formData = new FormData(this); // Create FormData object with form data

            $.ajax({
                url: 'ajx/ajxInsertPost.php', // Adjust the path to your PHP script
                type: 'POST',
                data: formData,
                contentType: false, // Tell jQuery not to set contentType
                processData: false, // Tell jQuery not to process the data
                success: function(response) {
                    var jsonResponse = JSON.parse(response);
                    if (jsonResponse.status === 'success') {
                        // alert(jsonResponse.message);
                        window.location.href = "main.php";
                        // Optionally, clear the form or redirect user
                    } else {
                        alert('Error: ' + jsonResponse.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                    alert('An error occurred while processing your request.');
                }
            });
        });
    });
</script>


</html>