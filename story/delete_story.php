<?php
  session_start();
  require '../databaseQA.php';
  if(!hash_equals($_SESSION['token'], $_POST['token'])){
    die("Request forgery detected");
  }
  $s_id = (int) trim($_POST['story_id']);
  $stmt = $mysqli->prepare("DELETE FROM Story WHERE story_id = '$s_id'");
  if(!$stmt){
      printf("Query Prep Failed: %s\n", $mysqli->error);
      exit;
  }
  $stmt->execute();
  $stmt->close();
  header("Location: story.php");
?>
