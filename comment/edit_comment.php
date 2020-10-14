<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
    integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <meta charset="utf-8">
    <link rel="stylesheet" href="../style.css">
    <title>Edit Comment</title>
  </head>
  <body>
    <div class="container">
      <div class="jumbotron">
        <h2>Edit Comment</h2>
        <form class="" action="edit_comment.php" method="post">
          <input type="hidden" name="comment_id" value="<?php echo(trim($_POST['comment_id']));?>">
          <p><label>Comments:</p>
          <textarea rows="10" cols="100" type="text" name="content_comment" required></textarea>
          <p>
            <input class="btn btn-primary btn-lg" type="submit" name="" value="Edit">
            <a class="btn btn-secondary btn-lg" href="comment.php">Cancel</a>
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
  if(isset($_POST['content_comment'])){
    $stmt = $mysqli->prepare("UPDATE Comment set content = ? WHERE comment_id = ?");
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $content = trim($_POST['content_comment']);
    $comment_id = (int) trim($_POST['comment_id']);
    $stmt->bind_param('si', $content, $comment_id);
    $stmt->execute();
    $stmt->close();
    header("Location: comment.php");
  }
?>
