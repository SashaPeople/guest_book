<?php
$host = 'localhost';
$port = '5432';
$dbname = 'testdb';
$user = 'sp';
$password = '4603';
$db = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password") or exit("Невозможно соединиться с сервером");
?>
