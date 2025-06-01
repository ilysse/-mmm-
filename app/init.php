<?php

require_once('config/config.php');


// including the classes
require_once 'database/connection.php';
require_once 'classes/Object.php';
require_once 'classes/User.php';

//include the function
require_once 'functions.php';



// makeing global objects
global $pdo;
session_start();
$obj = new Objects($pdo);
$Ouser = new User($pdo);

function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

$csrf_token = generate_csrf_token();








?>
