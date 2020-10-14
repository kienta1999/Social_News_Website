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
                <ul class="nav navbar-nav"><li><a class="navbar-link" href="story/story.php">Home</a></li></ul>
                <ul class="nav navbar-nav"><li><a class="navbar-link" href="logout.php"><?php echo($logout);?></a></li></ul>
            </div>
        </div>
    </nav>
    
    <div class="container">
      <div class="jumbotron">

        <?php
        if(isset($_GET["name"])){
            $username = $_GET["name"];
            require("databaseQA.php");
            $stmt = $mysqli->prepare("select full_name, email from User where username = ?");

              if(!$stmt){
                printf("Query Prep Failed: %s\n", $mysqli->error);
                exit;
              }
              $stmt->bind_param('s', $username);
              $stmt->execute();
              $stmt->bind_result($full_name, $email);
              $stmt->fetch();
              printf("<h2>Personal information</h2>");
              printf("<p>Username: %s </p>", $username);
              printf("<p>Full name: %s </p>", $full_name);
              printf("<p> Email: %s </p>", $email);
              $stmt->close();


              $stmt = $mysqli->prepare("select 	title, content, link from Story where username = ?");
              if(!$stmt){
                printf("Query Prep Failed: %s\n", $mysqli->error);
                exit;
              }
              $stmt->bind_param('s', $username);
              $stmt->execute();
              $stmt->bind_result($title, $content, $link);
              printf("<h2>Stories</h2>");
              while($stmt->fetch()){
                printf("<h3>Title: %s </h3>", $title);
                printf('<p><a href = "%s"> Visit site </a></p>', $link);
                printf("<p>Content: %s </p>", $content);
              }
              $stmt->close();  
        }
        else{
            header("Location: story/story.php");
        }
        ?>
      </div>
    </div>
  </body>
</html>
