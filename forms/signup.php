<?php
session_start();

include("connection.php");
include("functions.php");

if($_SERVER['REQUEST_METHOD'] == "POST") {

    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate input
    if(!empty($email) && !empty($password) && !is_numeric($email)) {   
        // Generate a unique user ID
        $user_id = random_num(20);

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert user data into the database
        $query = "INSERT INTO signup (user_id, firstname, lastname, email, password) VALUES ('$user_id', '$firstname', '$lastname', '$email', '$hashed_password')";
        $result = mysqli_query($con, $query);

if($result) {
    // Registration successful, redirect to login page
    header("Location: index.php");
    exit;
} else {
    // Registration failed
    echo "<script>alert('Registration failed')</script>";
    // Handle the failure, such as displaying an error message or logging it
 }
}
}
?> 


<!DOCTYPE html>
<html>
<head>
<title>Sign up</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
    
    <header>
        <h1>TechBoss</h1>
    </header>
      <section>
<div class="form-box2">
    <form method="POST">
        <h2>Sign up</h2>
 <div class="inputbox">
    <input type="text" name="firstname" required>
      <label for="">First Name</label>
</div>
   
  <div class="inputbox">
  <input type="text" name="lastname" required>
      <label for="">Last Name</label>
</div>


<div class="inputbox">
<input type="email" name="email" required>
        <label for="">Email</label>
</div>
    <div class="inputbox">
    <input type="password" name="password" required>
        <label for="">Password</label>
</div>
<div class="login-now">
    <button>Sign up</button><br></br>
    <p>Already have an account? <a href="index.php">Login now </a></p>
</div>
</div>
    </form>
</body>
</html>