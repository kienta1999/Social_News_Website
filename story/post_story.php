<?php
  session_start();
  require '../databaseQA.php';
  if(!hash_equals($_SESSION['token'], $_POST['token'])){
    die("Request forgery detected");
  }

  $title = trim($_POST['title']);
  $content = trim($_POST['content']);
  $username = $_SESSION['username'];
  $link = trim($_POST['link']);

  $stmt = $mysqli->prepare("insert into Story (username, title, content, link) values (?, ?, ?, ?)");
  if(!$stmt){
      printf("Query Prep Failed: %s\n", $mysqli->error);
      exit;
  }
  $stmt->bind_param('ssss', $username, $title, $content, $link);
  $stmt->execute();
  $stmt->close();
  header("Location: story.php");
?>
