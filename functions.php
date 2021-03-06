<?php 

function table_exist($table_name){
  global $db;
  $query = "SELECT tablename FROM pg_tables WHERE tablename = '$table_name'";
  $texist = pg_query($query) or exit ("Error"). pg_last_error();
  $res = pg_fetch_assoc($texist);
  if ($res['tablename'] == $table_name){
    return true;
  }else{
    return false;
  }
}

function user_exist($user_name){
  global $db;  
  $query = "SELECT name FROM users WHERE name = '$user_name'";
  $uexist = pg_query($query) or exit ("Error"). pg_last_error();
  $res = pg_fetch_assoc($uexist);
  if ($res['name'] == $user_name){
    return true;
  }else{
    return false;
  }
}

function get_user_id($user_name){
  global $db;  
  $query = "SELECT id FROM users WHERE name = '$user_name'";
  $uexist = pg_query($query) or exit ("Error"). pg_last_error();
  $res = pg_fetch_assoc($uexist);
  return $res['id']; 
}

function create_tmessages(){
  global $db;
  if (!table_exist("messages")){
    $query ="CREATE TABLE public.messages
(
    msg_id serial NOT NULL,
    text character varying(144) NOT NULL,
    user_id integer NOT NULL,
    parent_id integer,
    date timestamp(10) NOT NULL,
    PRIMARY KEY (msg_id)
)
WITH (
    OIDS = FALSE
);

ALTER TABLE public.messages
    OWNER to postgres;";
  pg_query($query);
  }
}

function create_tusers(){
  global $db;
  if (!table_exist("users")){
    $query = "CREATE TABLE public.users
(
    id serial NOT NULL,
    name character varying(100) NOT NULL,
    registration_date timestamp(10) NOT NULL,
    PRIMARY KEY (id)
)
WITH (
    OIDS = FALSE
);
ALTER TABLE public.users
    OWNER to postgres;";
  pg_query($query);
  }
}

function add_msg(){
  global $db;
  $date = date('Y/m/d H:i:s');
  $user_id = get_user_id($_POST['username']);
  $msg = pg_escape_string($db, $_POST['msg']);
  if (!empty($user_id && $msg)){
    $query = "INSERT INTO messages (text, user_id, date) VALUES($1,$2,$3)";
    $result = pg_prepare($db, "msg_query", $query);
    $result = pg_execute($db, "msg_query", array($msg, $user_id, $date));
  }
}

function add_reply(){
  global $db;
  $user_id = get_user_id($_POST['username']);
  $date = date('Y/m/d H:i:s');
  $msg = pg_escape_string($db, $_POST['comment']);
  $parent_id = $_POST['submit_reply']; 
  if (!empty($user_id && $msg)){
    $query = "INSERT INTO messages (text, user_id, parent_id, date) VALUES($1,$2,$3,$4)";
    $result = pg_prepare($db, "reply_query", $query);
    $result = pg_execute($db, "reply_query", array($msg, $user_id, $parent_id, $date));
  }
}

function add_user(){
  global $db;
  $name = pg_escape_string($db, $_POST['username']);
  if (user_exist($name) == false){
    $date = date('Y/m/d H:i:s');
    $query = "INSERT INTO users (name, registration_date) VALUES($1,$2)";
    $result = pg_prepare($db, "user_query", $query);
    $result = pg_execute($db, "user_query", array($name, $date));
  }
}


function get_data(){
  global $db; 
  $table_name = "messages";
  $table_name1 = "users";
  if (table_exist($table_name) == false){
    create_tmessages();
  }
  if (table_exist($table_name1) == false){
    create_tusers();
  }
  $query = "SELECT text, name, msg_id, parent_id, date FROM messages LEFT JOIN users ON (messages.user_id = users.id)";
  $res = pg_query($db, $query);
  $result = array();
    while ($row = pg_fetch_assoc($res)) {
        $result[$row["msg_id"]] = $row;
    }
    return $result;
}

function print_data($messages){
  foreach($messages as $message){
    if (empty($message['parent_id'])){
      tree($messages, $message['msg_id']);
    }
  }    
}

function tree($messages, $parent_id){
  echo "<ul>"; 
  include'message.php';
  foreach($messages as $message){
    if ($message['parent_id'] == $parent_id)
      tree($messages, $message['msg_id']);
  } 
  echo "</ul>"; 
}



function filtration(){
  global $db; 
  $from = $_POST['form']; 
  $to = $_POST['to']; 
  $query = "SELECT text, name, date FROM messages LEFT JOIN users ON (messages.user_id = users.id) WHERE date > '$from' AND date < '$to'";
  $res = pg_query($db, $query);
  return pg_fetch_all($res);
}
