<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
    integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <meta charset="utf-8">
    <link rel="stylesheet" href="../style.css">
    <title>Edit Story</title>
  </head>
  <body>
    <div class="container">
      <div class="jumbotron">
        <h2>Edit <i>"<?php echo(htmlentities(trim($_POST['title'])));?>"</i> Story</h2>
        <form class="" action="edit_story.php" method="post">
          <input type="hidden" name="story_id" value="<?php echo(htmlentities(trim($_POST['story_id'])));?>">
          <p><label>Title: <input type="text" name="title" value="<?php echo(htmlentities(trim($_POST['title'])));?>" required></label></p>
          <p><label>Link: <input type="text" name="link" value="<?php echo(htmlentities(trim($_POST['link'])));?>"></label></p>
          <p><label>Content:</p>
          <textarea rows="10" cols="100" type="text" name="content" required></textarea>
          <p>
            <input class="btn btn-primary btn-lg" type="submit" name="" value="Edit">
            <a class="btn btn-secondary btn-lg" href="story.php">Cancel</a>
          </p>
        </form>
      </div>
    </div>
  </body>
</html>
<?php
  session_start();
  if(isset($_POST['token']) && !hash_equals($_SESSION['token'], $_POST['token'])){
    die("Request forgery detected");
  }
  require '../databaseQA.php';
  if(isset($_POST['title']) && isset($_POST['content'])){
    $stmt = $mysqli->prepare("UPDATE Story set title = ?, content = ?, link = ? WHERE story_id = ?");
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $story_id = (int) trim($_POST['story_id']);
    $link = trim($_POST['link']);
    $stmt->bind_param('sssi', $title, $content, $link, $story_id);
    $stmt->execute();
    $stmt->close();
    header("Location: story.php");
  }
?>


