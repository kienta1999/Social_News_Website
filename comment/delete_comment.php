<?php
  session_start();
  if(!hash_equals($_SESSION['token'], $_POST['token'])){
    die("Request forgery detected");
  }
  require '../databaseQA.php';
  $comment_id = (int) trim($_POST['comment_id']);
  $stmt = $mysqli->prepare("DELETE FROM Comment WHERE comment_id = '$comment_id'");
  if(!$stmt){
      printf("Query Prep Failed: %s\n", $mysqli->error);
      exit;
  }
  $stmt->execute();
  $stmt->close();
  header("Location: comment.php");
?>
