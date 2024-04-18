<?php
session_start();
include('connection.php');

if(isset($_SESSION['user_id']) && isset($_GET['recipient_id'])) {
    $sender_id = $_SESSION['user_id'];
    $recipient_id = $_GET['recipient_id'];

    $stmt = $con->prepare("SELECT * FROM messages WHERE (sender_id = ? AND recipient_id = ?) OR (sender_id = ? AND recipient_id = ?) ORDER BY sent_at ASC");
    $stmt->bind_param("iiii", $sender_id, $recipient_id, $recipient_id, $sender_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $messages = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    // You may need to JSON encode the messages array before echoing it
    echo json_encode($messages);
}

?>