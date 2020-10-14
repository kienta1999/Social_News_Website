<?php
    session_start();
    unset($_SESSION['username']);
    unset($_SESSION['fullname']);
    unset($_SESSION['current_uname']);
    unset($_SESSION['story_id']);
    unset($_SESSION['title']);
    unset($_SESSION['content']);
    unset($_SESSION["expand_story_id"]);
    unset($_SESSION['token']);
    unset($_SESSION["link"]);
    session_destroy();
    header('Location: index.html');
?>