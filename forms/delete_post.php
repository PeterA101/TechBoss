<?php
session_start();
include('connection.php');

if (isset($_POST['post_id']) && !empty($_POST['post_id'])) {
    $postId = $_POST['post_id'];

    if (isset($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id'];

        // Debug code
        error_log("Attempting to delete post. Post ID: {$postId}, User ID: {$userId}");

        // Start a transaction
        $con->begin_transaction();

        try {
            $stmt = $con->prepare("DELETE FROM posts WHERE post_id = ? AND user_id = ?");
            $stmt->bind_param("ii", $postId, $userId); 
            if ($stmt->execute()) {
                echo "Post deleted successfully";
                $con->commit(); // Commit the transaction
            } else {
                // Output error message for debugging
                echo "Error deleting post: " . $stmt->error;
                $con->rollback(); // Rollback the transaction in case of error
            }
            $stmt->close();
        } catch (Exception $e) {
            $con->rollback(); // Rollback the transaction in case of error
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "User is not logged in.";
    }
} else {
    echo "Post ID not provided.";
}
?>
