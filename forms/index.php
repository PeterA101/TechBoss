<?php
session_start();

include("connection.php");

if($_SERVER['REQUEST_METHOD'] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    

    if(!empty($email) && !empty($password) && !is_numeric($email)) {
        //  prevent SQL injection
        $email = mysqli_real_escape_string($con, $email);

        // Query to fetch user data based on email
        $query = "SELECT * FROM signup WHERE email ='$email' LIMIT 1";
        $result = mysqli_query($con, $query);

        if($result && mysqli_num_rows($result) > 0) {
            $user_data = mysqli_fetch_assoc($result);
            // Verify the password
            if(password_verify($password, $user_data['password'])) {
                // Password matches so data is stored 
                $_SESSION['user_id'] = $user_data['user_id'];
                $_SESSION['email'] = $user_data['email'];
                // Redirect to home page

                // Storing first and last name 
                $_SESSION['firstname'] = $user_data['firstname']; 
                $_SESSION['lastname'] = $user_data['lastname']; 

                header("Location: home.php");
                die;
            } else {
                // Password does not match
                $error_message = "Invalid email or password.";
            }
        } else {
            // No user found with the provided email
            $error_message = "No user found with the provided email or password.";
        }
    } else {
        // Invalid input
        $error_message = "Please enter valid email and password.";
     }
    }

?>

<!DOCTYPE html>
<html>
<head>
<title>Sign in</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>TechBoss</h1>
    </header>
      <section>
        <div class="form-box">
            <form method="POST">
            <h2>Sign in</h2>
          <div class="inputbox">
            <input type="email" name="email" required>
              <label>Email</label>
</div>
    <div class="inputbox">
        <input type="password" name="password" required>
        <label>Password</label>
    </div>
    `<div class="forget">
        <label><input type="checkbox">Remember me  <a href="#">Forgotten Password</a></label>
    </div>
    <button>Login</button>
    <div class="register">
        <p>Don't have an account? <a href="signup.php">Register now </a></p>
      </form>
      <?php
    // Display error message if it exists
    if(isset($error_message)) {
        echo "<p>$error_message</p>";
    }
?>
     </div>
    </div>
</section>
</body>
</html>

