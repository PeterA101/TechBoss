<?php

function random_num($length)
{

  $text = "";
  if($length < 5)
  {
    $length = 5;
  }

  $len = rand(4, $length);

  for ($i=0; $i < $len; $i++){
    
    $text .= rand(0,9);
  }
     return $text;
}

function generatePostId($length, $conn) {
  do {
      $postId = "";
      $length = max($length, 4); // Ensure minimum length of 4
      for ($i = 0; $i < $length; $i++) {
          $postId .= rand(0, 9);
      }

      // Check if postId already exists in the database
      $query = "SELECT 1 FROM posts WHERE post_id = '$postId'";
      $result = mysqli_query($conn, $query);
  } while (mysqli_num_rows($result) > 0); // Keep generating if the ID already exists

  return $postId;
}
