<?php
session_start();

include 'includes/DB_con.php';
include 'includes/User.php';

$db = new DB_con();
$user = new User($db);

$user->logout();