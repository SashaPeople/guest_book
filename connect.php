<?php
$host = 'localhost';
$port = '5432';
$dbname = 'postgres';
$dbuser = 'postgres';
$password = '4603';
$db = pg_connect("host=$host port=$port dbname=$dbname user=$dbuser password=$password") or exit("Невозможно соединиться с сервером");
?>
