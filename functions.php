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
    id serial NOT NULL,
    text character varying(144) NOT NULL,
    user_id integer NOT NULL,
    date character varying(20) NOT NULL,
    PRIMARY KEY (id)
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
    date character varying(20) NOT NULL,
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
  $user_id = get_user_id($_POST['username']);
  $date = date('Y-M-d H:m-s');
  $msg = pg_escape_string($db, $_POST['msg']);
  $query = "INSERT INTO messages (text, user_id, date) VALUES('$msg','$user_id','$date')";
  pg_query($db, $query);
}

function add_user(){
  global $db;
  $name = pg_escape_string($db, $_POST['username']);
  if (user_exist($name) == false){
    $date = date('Y-M-d H:m-s');
    $query = "INSERT INTO users (name, date) VALUES('$name','$date')";
    pg_query($db, $query);
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
  $query = "SELECT * FROM users INNER JOIN messages on (users.id = messages.user_id)";
  $res = pg_query($db, $query);
  return pg_fetch_all($res);
}

?> 
