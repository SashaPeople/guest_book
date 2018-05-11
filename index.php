<?php
require_once 'functions.php';  
require_once 'connect.php';  
if(!empty($_POST["submit"])){
  add_user();
  add_msg();
  header("Location: {$_SERVER['PHP_SELF']}");
  exit;
}
if(empty($_POST["submit_filter"])){
  $messages = get_data();
}else{
  $messages = filtration();
}

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
  <div id="filter">
    <form method="post" name="filtration" action="">
      <p>Выберите дату:<p></br> 
      <div id="from">
        <input type="date" name="form" id="form">
      </div>
      <div id="to">
        <input type="date" name="to">
      </div>
      <button type="submit" name="submit_filter" value="test">submit</button>
    </form>
  </div>
  <div id="menu">
    <h3>Welcome to guest book</h3>
  </div>
  <div id="chatbox">
    <?php 
      print_data($messages);
    ?>
  </div>
    <form method="post" name="message" action="">
      <input name="username" type="text" id="username" size="63" /></br>
      <textarea name="msg" id="msg"></textarea></br>
      <button type="submit" name="submit" value="test">submit</button>
    </form>
</div>
</body>
</html>
