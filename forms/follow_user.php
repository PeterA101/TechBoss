<?php
session_start();
include('connection.php');

if (isset($_SESSION['user_id']) && isset($_POST['followed_id'])) {
    $followerId = $_SESSION['user_id'];
    $followedId = $_POST['followed_id'];

    // SQL to follow the user
    $sql = "INSERT INTO follows (follower_id, followed_id) VALUES (?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ii", $followerId, $followedId);

    if ($stmt->execute()) {
        // Follow successful
        echo "Followed successfully";
    } else {
        // Follow failed
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    // User ID to follow not provided or user not logged in
    echo "User ID to follow not provided or user not logged in.";
}
?>