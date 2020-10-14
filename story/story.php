<?php
  session_start();
  if(isset($_SESSION['username'])){
    $logout = "Logout";
  }
  else{
    $logout = "Log In";
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
    integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../style.css">
    <title>Story</title>
</head>
  <body>
    <nav class="navbar navbar-default navfont navbar-inverse navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <ul class="nav navbar-nav"><li><a class="navbar-link" href="story.php">Home</a></li></ul>
                <ul class="nav navbar-nav"><li><a class="navbar-link" href="../logout.php"><?php echo($logout);?></a></li></ul>
            </div>
        </div>
    </nav>
    
    <div class="container">
    <div class="jumbotron">
      <?php
          require '../databaseQA.php';
          if(isset($_SESSION['fullname'])){
            $fullname = $_SESSION['fullname'];
            $username = trim($_SESSION['username']);
            $url = "../user_profile.php" . "?name=$username";
            ?>
             
            <form action="../user_profile.php" method="get">
              <input type="hidden" name="name" value= "<?php echo (htmlentities($username)); ?>">
              <h1>Welcome to Viet News, <input class = "button_link" type="submit" value="<?php echo (htmlentities($fullname)); ?>">!</h1>
            </form>
            
          <?php
          }
          else{
            echo ("<h1>Welcome to Viet News!</h1>");
          }
        if(isset($_SESSION['username'])){
          ?>
          <h2>New Story</h2>
          <form class="" action="post_story.php" method="post">
            <p><label>Title: <input type="text" name="title"></label></p>
            <p><label>Link: <input type="text" name="link"></label></p>
            <p><label>Content:</label></p>
            <textarea class="form-control" rows="10" cols="100" name="content"></textarea>
            <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
            <p><input class="btn btn-primary btn-lg" type="submit" value="Post"></p>
          </form>

      <?php
      }
      if(isset($_SESSION['username'])){
        $username = trim($_SESSION['username']);
      }
      else{
        $username = NULL;
      }
      $stmt = $mysqli->prepare("select username, story_id, title, content, link from Story");
      if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
      }
      $stmt->execute();

      $stmt->bind_result($uname, $s_id, $title, $content, $link);
      if(!isset($_SESSION["expand_story_id"])){
        $_SESSION["expand_story_id"] = array();
      }
      while($stmt->fetch()){
        $url = "../user_profile.php" . "?name=$uname";
        printf("<h2 class='inline'>%s</h2> <h3 class='inline'><i>(<a href= '%s'>%s</a>)</i></h3> ", $title, $url, $uname);
        if($link){
          printf('<p><a href = "%s"> Visit site </a></p>', $link);
        }
        if(strlen($content) > 100 && !in_array("$s_id", $_SESSION["expand_story_id"])){
          $shorternContent = substr($content, 0, 100) . "...";
          printf("<p class='inline'> %s </p>", $shorternContent);
          ?>
          <form class="inline-button" action="story_expand.php" method="post">
            <input type="hidden" name="expand_story_id" value = <?php echo($s_id);?> >
            <button type="submit">View More</button>
          </form>
          <?php
          
        }
        else{
          printf("<p> %s </p>", $content);
        }
        if($username == $uname){
          ?>
          <div>
            <!-- Delete Story -->
            <form class="inline" action="delete_story.php" method="post">
              <input type="hidden" name="story_id" value="<?php echo(htmlentities($s_id));?>">
              <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
              <input class="btn btn-danger" type="submit" name="delete" value="Delete" />
            </form>

            <!-- Edit Story -->
            <form class="inline" action="edit_story.php" method="post">
              <input type="hidden" name="story_id" value="<?php echo(htmlentities($s_id));?>">
              <input type="hidden" name="title" value="<?php echo(htmlentities($title));?>">
              <input type="hidden" name="link" value="<?php echo(htmlentities($link));?>">
              <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
              <input class="btn btn-primary" type="submit" name="edit" value="Edit" />
            </form>
          </div>
          <?php
        }
        ?>
        <br>
        <!-- See comments -->
        <form action="../comment/comment.php" method="post">
          <input type="hidden" name="uname" value = "<?php echo(htmlentities($uname));?>">
          <input type="hidden" name="story_id" value="<?php echo(htmlentities($s_id));?>">
          <input type="hidden" name="title" value = "<?php echo(htmlentities($title));?>">
          <input type="hidden" name="link" value = "<?php echo(htmlentities($link));?>">
          <input type="hidden" name="content" value = "<?php echo(htmlentities($content));?>">
          <button class="btn btn-primary" type="submit">View Comments</button>
        </form>
        <br>

        <?php
      }

      $stmt->close();
      // print_r($_SESSION["expand_story_id"]);
      ?>

    </div>
    </div>

  </body>
</html>
