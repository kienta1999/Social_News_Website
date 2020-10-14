<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
    integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <meta charset="utf-8">
    <link rel="stylesheet" href="style.css">
    <title>Users Sign Up</title>
  </head>
  <body>
    <nav class="navbar navbar-default navfont navbar-inverse navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <ul class="nav navbar-nav"><li><a class="navbar-link" href="index.html">Log In</a></li></ul>
                <ul class="nav navbar-nav"><li><a class="navbar-link" href="register.html">Register</a></li></ul>
            </div>
        </div>
    </nav>

    <?php
      require 'databaseQA.php';
      $fullname = trim($_POST['fullname']);
      $email = trim($_POST['email']);
      $username = trim($_POST['username']);
      $password = trim($_POST['password']);
      
      $stmt = $mysqli->prepare("SELECT username FROM User WHERE username =?");
      checkError($stmt);
      $stmt->bind_param('s', $username);
      $stmt->execute();
      $result = $stmt->get_result();
      if($result->fetch_assoc()){
        ?>
        <div class="container">
          <div class="jumbotron">
            <h2>This username is already in use. Please use another.</h2>
              <form action="register.html">
                  <input type="submit" value="Back">
              </form>
          </div>
        </div>
  </body>
</html>
        <?php
        exit;
      }
      $stmt->close();

      //Validate password strength
      $uppercase = preg_match('@[A-Z]@', $password);
      $lowercase = preg_match('@[a-z]@', $password);
      $number    = preg_match('@[0-9]@', $password);
      $username_space = strpos($username, " ");
      
      if(!$uppercase || !$lowercase || !$number || strlen($password) < 8 || $username_space) {
        if($username_space){
          $message = "Error: Username cannot contain any spaces.";
        }
        else if(!$uppercase){
          $message = "Error: The password you inputted should include an upper case letter";
        }
        else if(!$lowercase){
          $message = "Error: The password you inputted should include an lower case letter";
        }
        else if(!$number){
          $message = "Error: The password you inputted should include a number";
        }
        else{
          $message = "Error: The password you inputted should be at least 8 characters";
        }
        ?>
        <div class="container">
          <div class="jumbotron">
            <h3><?php echo($message) ?></h3>
            <p>Password must contain the following: </p>
            <ul>
              <li>A <b>lowercase</b> letter</li>
              <li>An <b>uppercase</b> letter</li>
              <li>A <b>number</b></li>
              <li>Minimum <b>8 characters</b></li>
            </ul>
            <form action="register.html">
                <input type="submit" value="Back">
            </form>
          </div>
        </div>
  </body>
</html>
        <?php
        exit;
      }
      $password = password_hash($password, PASSWORD_BCRYPT);

      function checkError($stmt) {
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
      }
      
      $stmt = $mysqli->prepare("insert into User (full_name, email, username, password) values (?, ?, ?, ?)");
      checkError($stmt);
      $stmt->bind_param('ssss', $fullname, $email, $username, $password);
      $stmt->execute();
      $stmt->close();

      header("Location: index.html");
    ?>