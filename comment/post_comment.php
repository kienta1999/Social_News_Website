<?php
  session_start();
  if(!hash_equals($_SESSION['token'], $_POST['token'])){
    die("Request forgery detected");
  }
  $username = $_SESSION['username'];
  require '../databaseQA.php';

  $content = trim($_POST['content']);
  // $_SESSION['title'] = $title;
  $story_id = (int) trim($_POST['story_id']);
  $username = $_SESSION['username'];

  $stmt = $mysqli->prepare("insert into Comment (story_id, username, content) values (?, ?, ?)");
  if(!$stmt){
      printf("Query Prep Failed: %s\n", $mysqli->error);
      exit;
  }
  $stmt->bind_param('iss', $story_id, $username, $content);
  $stmt->execute();
  $stmt->close();
  header("Location: comment.php");
?>
