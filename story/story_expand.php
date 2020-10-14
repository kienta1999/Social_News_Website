<?php
session_start();
$expand_story_id = (int) trim($_POST["expand_story_id"]);
array_push($_SESSION["expand_story_id"], $expand_story_id);
header("Location: story.php");
?>