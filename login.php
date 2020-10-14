<?php
  require 'databaseQA.php';

  function checkError($stmt) {
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
  }

  $username = $_POST['username'];
  $username = trim($username);

  $password = $_POST['password'];
  $password = trim($password);

  $stmt = $mysqli->prepare("SELECT password, full_name FROM User WHERE username = ?");
  checkError($stmt);
  $stmt->bind_param('s', $username);
  $stmt->execute();
  $stmt->bind_result($pass, $fullname);
  $user = $stmt->fetch();
  if ($user){
    if(password_verify ($password, $pass)){
        session_start();
        $_SESSION['username']  = $username;
        $_SESSION['fullname']  = $fullname;
        $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
        echo ("Login Successfull");
        header("Location: story/story.php");
    }
    else{
        echo ("Password not correct");
        header("Location: userNotFound.html");
    }

  }
  else{
    echo ("Username not found");
    header("Location: userNotFound.html");
  }
  $stmt->close();
?>
