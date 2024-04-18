<?php

session_start();
include("connection.php");

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if user is not authenticated
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id']; 

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Get the pitch text from the form
    $pitch_text = $_POST['pitch'];

    // Validate input
    if (!empty($pitch_text)) {
        // Insert pitch data into the database
        $query = "INSERT INTO pitches (user_id, pitch_text) VALUES (?, ?)";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "is", $user_id, $pitch_text);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            echo "<script>alert('Pitch submitted successfully')</script>";
            // Redirect to a confirmation page or display a success message
        } else {
            echo "<script>alert('Submission failed')</script>";
          
        }
    } else {
        // Pitch text is empty
        echo "<script>alert('Please write a pitch before submitting')</script>";
    }
}
?>


<!DOCTYPE html>
<html>
<head>
<title>TechBoss</title>
<link rel="stylesheet" href="pitch-ideas.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
<body>
    <nav>
    <div class="container">
            <h2 class="log">
              <a href="home.php">TechBoss</a>
            </h2>
            <div class="search-bar">
               <span class="material-symbols-outlined">search</span>
               <input type="search" placeholder="Search for entrepreneurs" id="user-search">
            <!-- searching for results -->
            <div class="search-results" id="search-results" style="display: none;">

             </div>
            </div>

            <div class="create">
                <label class="btn btn-primary" for="create-post">Create</label>
            <div class="profile-photo" id="profilePhoto">
                <img src="./images/avatar-1.jpg" alt="Profile">
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="logout.php">Logout</a>
                </div>
                </div>
               </div>
              </div>
          </div>
        </nav>
          <!-- Pitch idea stmt -->
          <div class="pitch-idea-container">
                <div class="title">
                    <h1> Pitch an Idea with TechBoss </h1>
                </div>
             <div class="content-wrapper">
                <div class="content-container"> 
                  <div class="intro">
            <p>
            TechBoss invites you to transform your visionary ideas into reality. Recognizing the power of innovation to reshape our world, 
            we're on the lookout for brilliant concepts that can make a positive difference in people's lives. <br></br>
            At TechBoss, we provide a dynamic platform for you to present your business proposal in 200 words or less. 
            In partnership with investors, we offer you a golden opportunity to grab their attention. <br></br>
            Should your idea captivate our network of investors and be recognized for its creativity, 
            you could secure the funding to transform your ideas into something amazing.
          Don't let this chance slip through your fingers. Submit your ideas and join us in our mission to change the world together. <br></br>
          </p>
        </div>
        <div class="pitch-image">
    <img src="./images/worldinpalms.jpg" alt="Motivational image">
              </div>
        <div class="pitch-form-container">
    <h2 class="form-title">Craft your Pitch</h2>
    <form action="pitch-ideas.php" method="POST">
    <textarea name="pitch" id="pitch-text" placeholder="Enter your 100-200 word pitch here..." required minlength="100" maxlength="1200"></textarea>
            <button type="submit" class="btn btn-primary">Submit Your Pitch</button>
        </div>
      </form>
     </div>
   </div>
            </div>
         </div>
      </div>
  </body>
<html>