<?php
session_start();
     include('connection.php');

     if (!isset($_SESSION['user_id'])) {
        header("Location: index.php"); // Redirect to login page if user is not authenticated
        exit;
    }

    $currentUser = $_SESSION['user_id'];


    // Fetching the list of users that the logged-in user is following
    $followedUsersQuery = "SELECT * FROM signup WHERE user_id IN (SELECT followed_id FROM follows WHERE follower_id = ?)";
    $stmt = $con->prepare($followedUsersQuery);
    $stmt->bind_param("i", $currentUser);
    $stmt->execute();
    $result = $stmt->get_result();
    $followedUsers = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    $query = "SELECT posts.post_id, posts.post, posts.date, signup.firstname
    FROM posts
    JOIN signup ON posts.user_id = signup.user_id
    ORDER BY posts.date DESC"; // displays recent posts first
    $result = mysqli_query($con, $query);

    $followingAnyone = true; // Assume the user is following someone
    if (empty($followedUsers)) {
    $followingAnyone = false; // False if empty
    $notFollowingAnyoneMessage = "You are not following anyone yet.";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>TechBoss</title>
<link rel="stylesheet" href="style-hp.css">
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
            <a href="pitch-ideas.php" class="btn btn-primary">Pitch an Idea</a>
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
    <!--main section of homepage -->
    <main>
       <div class="container">
        <!--Left -->
          <div class="left">
            <a class="profile">
                <div class="profile-photo">
                    <img src="./images/avatar-1.jpg">
                </div>
                <div class="display-name">
                <?php 
                    // Check if the session variables are set before trying to display them
                    if(isset($_SESSION['firstname']) && isset($_SESSION['lastname'])) {
                        echo htmlspecialchars($_SESSION['firstname']) . ' ' . htmlspecialchars($_SESSION['lastname']);
                    }
                    ?>
                </h4>
                <p class="text-muted">
                    <?php 
                    // $_SESSION['email'] will always be set if a user is logged in
                    echo htmlspecialchars( $_SESSION['email']); 
                    ?>
                 </div>
            </a>
            <!--Side Bar -->
            <div class="sidebar">
                <a class="menu-item active">
                  <span><i class="material-symbols-outlined">home</i></span><h3>Home</h3>
                </a>
                <a class="menu-item " id="notifications">
                  <span><i class="material-symbols-outlined">Notifications</i></span><h3>About</h3>
                  <!--Notifications Popup-->
                  <div class="notifications-popup">
                   <div>
                  <div class="profile-photo">
                    <img src="./images/avatar-1.jpg">
                </div>
                <div class="notification-body">
                    <b>Alan Smith</b> accepted your connection request
                    <small class="text-muted">2 DAYS AGO</small>
                   </div>
                </div>
            </div>
            
            <!---end of noifications popup-->
            

                <a class="menu-item">
                <span><i class="material-symbols-outlined">settings</i></span><h3>Settings</h3>
                </a>


               
                <!--The End of the Sidebar-->
                <label for="create-post" class="btn btn-primary">New Post</label>
            </div>
</div>


            <!--Middle of homepage -->
           <div class="middle">

           <!--Create Post Feature -->
           <form class="create-post" action="post.php" method="POST">
                <div class="profile-photo">
                    <img src="./images/avatar-1.jpg">
                </div>
                <input type="text" placeholder="what are you thinking about?" id="create-post" name="post">
                <input type="submit" value="Post" class="btn btn-primary">
            </form>
       
           <!--display feeds -->
           
           <div class="feeds">
             <?php while($post = mysqli_fetch_assoc($result)): ?>
            <!--feed 1 -->
                <div class="feed">
                    <div class="head">
                        <div class="user">
                            <div class="profile-photo">
                                <img src="./images/avatar-1.jpg">
                            </div>
                              <div class="info">
                              <h3><?php echo isset($_SESSION['firstname']) ? htmlspecialchars($_SESSION['firstname']) : 'Guest'; ?></h3>
                             <!-- <small>London, 15 minutes ago</small> -->
                              </div>
                        </div> 
                        <span class="edit">
                     <i class="material-symbols-outlined">more_horiz</i>
                        <div class="post-options" style="display:none;">
                      
             <a href="#" class="delete-post" data-postid="<?php echo htmlspecialchars($post['post_id']); ?>">Delete Post</a>
          </div>
       </span>
    </div>
                 <!--
                    <div class="photo">
                        <img src="./images/feed-2.jpg">
                    </div> -->
                    <!--like and comment icons -->
                    
                    <div class="action-buttons">
                        <div class="interaction-buttons">
                         <!--
                        <span><i class="material-symbols-outlined">favorite</i></span>
                        <span><i class="material-symbols-outlined">comment</i></span>
             -->
                    </div>
                    
       
</div>
             
             
             
                    <!--
                    <div class="liked-by">
                        <span><img src="./images/profile-pic1.png"></span>
                        <span><img src="./images/profile-pic2.jpg"></span>
                        <p>Liked by <b>Michael A</b> and <b> 10 others</b></p>
                    </div>
             -->

                    <div class="caption">
                    <p><b><?php echo htmlspecialchars($post['firstname']); ?></b> <?php echo htmlspecialchars($post['post']); ?></p>                    
                 </div>
                    <div class="comments text-muted">View all 
                        5 comments</div>
                   </div>
                   <?php endwhile; ?>
                </div>
             </div>


    

            <!-- right side --> 
             <div class="right">
                <div class="messages">
                    <div class="heading">
                        <h4>Messages</h4><i class="material-symbols-outlined">edit_square</i>
                    </div>
                    <!--Search Bar -->
                    <div class="search-bar">
                    <i class="material-symbols-outlined">search</i>
                    <input type="search" placeholder="Search message" id="message-search">
                    </div>

                    <!-- chat container -->
         <div class="chat-container" id="chatContainer">
           <div class="chat-header">
             <span>Chat with <span id="chatWith">Select a User</span></span>
            <!-- You can add a button or link to close the chat -->
        </div>
        <div id="messageArea">
            <!-- Chat messages will be loaded here -->
        </div>
        <div class="chat-input">
            <input type="text" id="chatMessage" placeholder="Type a message...">
            <button onclick="sendMessage()">Send</button>
        </div>
    </div>
                    <!-- MESSAGES -->
                    <div class="category">
                        <h6 class="active">Primary</h6>
                        <h6>General</h6>
                        <h6 class="message-requests">requests(1)</h6>
                    </div>
                    <!-- The Message -->
                    <div class="followed-users-list">
    <?php if ($followingAnyone): ?>
        <?php foreach ($followedUsers as $followedUser): ?>
            <div class="followed-user" data-recipient-id="<?= htmlspecialchars($followedUser['user_id']); ?>" onclick="selectRecipient(this.getAttribute('data-recipient-id'))">
             <?= htmlspecialchars($followedUser['firstname']) . ' ' . htmlspecialchars($followedUser['lastname']); ?>
            </div>

        <?php endforeach; ?>
    <?php else: ?>
        <p><?php echo $notFollowingAnyoneMessage; ?></p>
    <?php endif; ?>
</div>

                <!-- Start of Message -->
        <div class="message-composer">
            <form method="POST" action="send_message.php">
            <textarea name="message" placeholder="Write a message..." required></textarea>
            <input type="hidden" id="recipient_id" name="recipient_id" value="">
            <input type="submit" name="send_message" value="Send" class="btn btn-primary">
          </form>
       </div> 
                
  </main>
  <script>
//When the user clicks on the profile, dropdown menu will appear
document.addEventListener("DOMContentLoaded", function() {

    var profilePhotoElement = document.getElementById('profilePhoto');
    if (profilePhotoElement) {
        profilePhotoElement.onclick = function() {
            var dropdownMenu = this.querySelector('.dropdown-menu');
            dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
        };
    }
      //Fetch messages from recipient
    function fetchMessages(recipientId) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'fetch-messages.php?recipient_id=' + recipientId, true);
        xhr.onload = function() {
            if (this.status == 200) {
                var messages = JSON.parse(this.responseText);
                displayMessages(messages);
            } else {
                console.error('Failed to retrieve messages.');
            }
        };
        xhr.send();
    }
     //display mention on the page
    function displayMessages(messages) {
        var messageArea = document.getElementById('messageArea');
        messageArea.innerHTML = '';
        messages.forEach(function(message) {
            var messageDiv = document.createElement('div');
            messageDiv.classList.add('message');
            messageDiv.textContent = message.sender + ': ' + message.text;
            messageArea.appendChild(messageDiv);
        });
    }
      //Displays the chat
    function showChat(recipientId, recipientName) {
        var chatContainer = document.getElementById('chatContainer');
        var chatWith = document.getElementById('chatWith');
        chatWith.textContent = recipientName;
        fetchMessages(recipientId);
        chatContainer.style.display = 'block';
    }
      //Handle the form submission for sending messages
    var messageForm = document.querySelector('.message-composer form');
    if (messageForm) {
        messageForm.addEventListener('submit', function(e) {
            e.preventDefault();
            var messageInput = document.querySelector('.message-composer textarea');
            //AJAX request to send the message
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'send_message.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            var formData = 'send_message=true&recipient_id=' +
                           encodeURIComponent(document.getElementById('recipient_id').value) +
                           '&message=' + encodeURIComponent(messageInput.value);
            xhr.onload = function() {
                if (this.status == 200 && this.responseText.trim() === "Message sent!") {
                    console.log('Message sent successfully');
                    messageInput.value = '';
                } else {
                    console.error('Failed to send message.', this.responseText);
                }
            };
            xhr.send(formData);
        });
    }
    //Search bar handling
    var searchField = document.getElementById('user-search');
    var searchResults = document.getElementById('search-results');
    if (searchField) {
        searchField.addEventListener("keyup", function(e) {
            var searchQuery = e.target.value;
            if (searchQuery.length > 0) {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'search_users.php', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (this.responseText.trim().length > 0) {
                        searchResults.innerHTML = this.responseText;
                        searchResults.style.display = 'block';
                    } else {
                        searchResults.style.display = 'none';
                    }
                };
                xhr.send('query=' + encodeURIComponent(searchQuery));
            } else {
                searchResults.style.display = 'none';
            }
        });
    }
    //Event delegation for dynamic follow buttons
    document.body.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('follow-button')) {
            e.preventDefault();
            var button = e.target;
            var followedId = button.getAttribute('data-userid');
            //Request to follow a user
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'follow_user.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (this.status == 200) {
                    button.textContent = 'Following';
                    button.disabled = true;
                } else {
                    alert('Failed to follow the user. Please try again.');
                }
            };
            xhr.send('followed_id=' + encodeURIComponent(followedId));
        }
         //Deleting posts
        if (e.target && e.target.matches('.delete-post')) {
            e.preventDefault();
            var postId = e.target.getAttribute('data-postid');
            if (confirm('Are you sure you want to delete this post?')) {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'delete_post.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (this.status == 200) {
                        location.reload();
                    } else {
                        alert('Failed to delete the post. Please try again.');
                    }
                };
                xhr.send('post_id=' + encodeURIComponent(postId));
            }
        }
    });
     //Hide post options when clicking elsewhere
    window.onclick = function(event) {
        var dropdowns = document.getElementsByClassName("post-options");
        if (!event.target.matches('.edit') && !event.target.matches('.material-symbols-outlined')) {
            Array.prototype.forEach.call(dropdowns, function(dropdown) {
                dropdown.style.display = 'none';
            });
        }
    };
     //Handling recipient messaging
    function selectRecipient(recipientId) {
        var recipientInput = document.getElementById('recipient_id');
        var recipientName = '';
        var followedUserDivs = document.querySelectorAll('.followed-user');
        followedUserDivs.forEach(function(userDiv) {
            if(userDiv.getAttribute('data-recipient-id') === recipientId) {
                userDiv.classList.add('selected');
                recipientName = userDiv.textContent.trim();
            } else {
                userDiv.classList.remove('selected');
            }
        });
        if (recipientInput) {
            recipientInput.value = recipientId;
        }
        showChat(recipientId, recipientName);
    }

    document.addEventListener('click', function(e) {
        if (e.target && e.target.matches('.followed-user')) {
            var recipientId = e.target.getAttribute('data-recipient-id');
            selectRecipient(recipientId);
        }
    });
});

   
    document.addEventListener("click", function(event) {
        // If the clicked element is part of 'edit' or is the 'more_horiz' icon itself
        if (event.target.matches('.edit, .edit *')) {
            
            var editElement = event.target.closest('.edit');

            // Toggle the display of the '.post-options' div within the clicked '.edit' element
            var postOptions = editElement.querySelector('.post-options');
            if (postOptions.style.display === 'none' || !postOptions.style.display) {
                postOptions.style.display = 'block';
            } else {
                postOptions.style.display = 'none';
            }

            // Prevent the click from affecting other elements
            event.stopPropagation();
        } else {
            
            var allPostOptions = document.querySelectorAll('.post-options');
            allPostOptions.forEach(function(div) {
                div.style.display = 'none';
            });
        }
    });


</script> 
</body>
</html>
