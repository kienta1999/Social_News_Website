<?php
  session_start();
  if(isset($_SESSION['username'])){
    $logout = "Logout";
  }
  else{
    $logout = "Login";
  }
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
    integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <meta charset="utf-8">
    <link rel="stylesheet" href="../style.css">
    <title>Comment</title>
  </head>
  <body>
  <nav class="navbar navbar-default navfont navbar-inverse navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <ul class="nav navbar-nav"><li><a class="navbar-link" href="../story/story.php">Home</a></li></ul>
                <ul class="nav navbar-nav"><li><a class="navbar-link" href="../logout.php"><?php echo($logout);?></a></li></ul>
            </div>
        </div>
    </nav>
    <div class="container">
      <div class="jumbotron">
      <?php
          if(isset($_POST["uname"])){
              $_SESSION["current_uname"] = trim($_POST["uname"]);
              $_SESSION["story_id"] = (int) trim($_POST["story_id"]);
              $_SESSION["title"] = trim($_POST["title"]);
              $_SESSION["content"] = trim($_POST["content"]);
              $_SESSION["link"] = trim($_POST["link"]);
          }
          $uname = $_SESSION["current_uname"];
          $story_id = (int) $_SESSION["story_id"];
          $title = $_SESSION["title"];
          $content = $_SESSION["content"];
          $link = $_SESSION["link"];
          if(isset($_SESSION['username'])){
            $username = $_SESSION['username'];
          }
          else{
            $username = NULL;
          }
          $url = "../user_profile.php" . "?name=" . $uname;
          printf("<h2 class='inline'>%s</h2> <h3 class='inline'><i>(<a href= '%s'>%s</a>)</i></h3> ", $title, $url, $uname);
          printf('<p><a href = "%s"> Visit site </a></p>', $link);
          printf("<p> %s </p>", $content);
      ?>

      <?php
      if($username != NULL){
        ?>
        <form action="post_comment.php" method="post">
            <p>Comment: <textarea class="form-control" rows="3" cols="100" name="content"></textarea> </p>
            <input type="hidden" name="story_id" value = <?php echo(htmlentities($story_id));?>>
            <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
            <input type="submit" value="Submit">
        </form>

        
        <?php
      }
          require '../databaseQA.php';

          $stmt = $mysqli->prepare("select comment_id, username, content from Comment where story_id = ?");
          if(!$stmt){
              printf("Query Prep Failed: %s\n", $mysqli->error);
              exit;
          }
          $stmt->bind_param('i', $story_id);
          $stmt->execute();

          $stmt->bind_result($comment_id, $uname, $content);

          while($stmt->fetch()){
              $url = "../user_profile.php" . "?name=" . $uname;
              printf("<p><i><a href= '%s'> %s</a></i>: %s</p>", $url, $uname, $content);
              if($uname == $username){
                ?>
                <div>
                <!-- Delete Comment -->
                <form class="inline" action="delete_comment.php" method="post">
                  <input type="hidden" name="comment_id" value="<?php echo(htmlentities($comment_id));?>">
                  <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
                  <input class="btn btn-danger" type="submit" name="delete" value="Delete" />
                </form>

                <!-- Edit Comment -->
                <form class="inline" action="edit_comment.php" method="post">
                  <input type="hidden" name="comment_id" value="<?php echo(htmlentities($comment_id));?>">
                  <input type="hidden" name="content" value="<?php echo(htmlentities($content));?>">
                  <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
                  <input class="btn btn-primary" type="submit" name="edit" value="Edit" />
                </form>
                </div>
                <?php
              }
              echo("<br>");
          }
          $stmt->close();
      ?>

    </div>
    </div>
  </body>
</html>
