<?php
session_start();
include("connection.php"); 

if(isset($_POST['send_message']) && isset($_SESSION['user_id'])) {
    $sender_id = $_SESSION['user_id']; // Sender's user ID from the session
    $recipient_id = $_POST['recipient_id']; // Recipient's user ID from the form
    $message = $_POST['message']; 

    // Prevent SQL injection and ensure proper escaping of the message
    $message = $con->real_escape_string($message);

    // Prepare the INSERT statement
    $stmt = $con->prepare("INSERT INTO messages (sender_id, recipient_id, message) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $sender_id, $recipient_id, $message);

    // Execute and check if successful
    if($stmt->execute()) {
        echo "Message sent!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Message was not sent. Ensure all fields are filled in.";
}
?>

