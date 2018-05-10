<?php
require_once 'functions.php';  
require_once 'connect.php';  
if(!empty($_POST["submit"])){
  add_user();
  add_msg();
  header("Location: {$_SERVER['PHP_SELF']}");
  exit;
}
$messages = get_data();
?> 
<!DOCTYPE html>
<html lang = "en";>
<head>
  <meta charset="UTF-8">
  <title>Guest book</title>
  <link type="text/css" rel="stylesheet" href="style.css" />
</head>
<body>
<div id="wrapper">
  <div id="menu">
        <h3>Welcome to guest book</h3>
        <div style="clear:both"></div>
    </div>
    <div id="chatbox">
    <?php 
      print_data($messages);
    ?>
    </div>
    <form method="post" name="message" action="">
        <input name="username" type="text" id="username" size="63" /></br>
        <textarea name="msg" id="msg"></textarea></br>
        <button type="submit" name="submit" value="test">submmit </button>
    </form>
</div>
</body>
</html>
