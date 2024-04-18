<?php
session_start();
include("connection.php");
include("functions.php");
//This page is for when a user creates a new post
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['post'])) {
    $postContent = mysqli_real_escape_string($con, $_POST['post']);
    $userId = mysqli_real_escape_string($con, $_SESSION['user_id']);  

    if (!empty($postContent)) {
        $postId = generatePostId(19, $con);

        // SQL command inserting into the 3 columns
        
        $sql = "INSERT INTO posts (post_id, user_id, post) VALUES ('$postId', '$userId', '$postContent')";

        // Execute the query
        if (mysqli_query($con, $sql)) {
            header("Location: home.php");
            exit;
        } else {
            echo "Error: " . mysqli_error($con);
        }
    } else {
        echo "Post content cannot be empty.";
    }
}

mysqli_close($con);
?>