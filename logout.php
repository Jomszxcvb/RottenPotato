<?php
session_start();

include 'includes/DB.php';
include 'includes/User.php';

$db = new DB();
$user = new User($db);

$user->logout();