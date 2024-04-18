<?php
session_start(); // Start the session if not already started
include('connection.php');

// Get the search term from the AJAX request
$searchQuery = $_POST['query'] ?? '';

// Protect against SQL injection
$searchQuery = mysqli_real_escape_string($con, $searchQuery);

// searching for users 
$query = "SELECT user_id, firstname, lastname FROM signup WHERE firstname LIKE '%$searchQuery%' OR lastname LIKE '%$searchQuery%'";
$result = mysqli_query($con, $query);

// Check if there are any results
if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        // Output each user as a block of HTML
        echo "<div class='search-result'>";
        echo "<p>" . htmlspecialchars($row['firstname']) . " " . htmlspecialchars($row['lastname']) . "</p>";

        // Add a "Follow" button if the user is not the currently logged-in user
        if (isset($_SESSION['user_id']) && $_SESSION['user_id'] != $row['user_id']) {
            // The button has a data attribute `data-userid` which holds the user_id of the user to follow
            echo "<button class='follow-button' data-userid='" . htmlspecialchars($row['user_id']) . "'>Follow</button>";
        }
        echo "</div>";
    }
} else {
    echo "No users found";
}
?>
