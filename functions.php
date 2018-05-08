<?php 

function exist($tname){
  global $db;
  $query = "SELECT tablename FROM pg_tables WHERE tablename = '$tname'";
  $texist = pg_query($query) or exit ("Error"). pg_last_error();
  $res = pg_fetch_assoc($texist);
  if ($res['tablename'] == $tname){
    return true;
  }else{
    return false;
  }
}

function create_tmessages(){
  global $db;
  if (!exist("messages")){
    $query = "CREATE TABLE public.messages
(
    id serial NOT NULL,
    text character varying(144) NOT NULL,
    date character varying(20) NOT NULL,
    PRIMARY KEY (id)
)
WITH (
    OIDS = FALSE
);

ALTER TABLE public.messages
    OWNER to sp;";
  pg_query($query);
  }
}

function create_tusers(){
  global $db;
  if (!exist("users")){
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
    OWNER to sp;";
  pg_query($query);
  }
}
function add_msg(){
  global $db;
  $table_name = "messages";
  if (exist($table_name) == false){
    create_tmessages();
  }
  $date = date('Y-M-d H:m-s');
  $msg = pg_escape_string($db, $_POST['msg']);
  $query = "INSERT INTO messages (text, date) VALUES('$msg','$date')";
  pg_query($db, $query);
}

function add_user(){
  global $db;
  $table_name = "users";
  if (exist($table_name) == false){
    create_tusers();
  }
  $table_name = "users";
  exist($table_name);
  $name = pg_escape_string($db, $_POST['username']);
  $date = date('Y-M-d H:m-s');
  $query = "INSERT INTO users (name, date) VALUES('$name','$date')";
  pg_query($db, $query);
}

function get_data(){
  global $db; 
  $query = "SELECT * FROM users INNER JOIN messages on (messages.id = users.id)";
  $res = pg_query($db, $query);
  return pg_fetch_all($res, PGSQL_ASSOC);
}

?> 
