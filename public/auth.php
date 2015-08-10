<?php

$user = "admin";
$pass = "1";

$storedUser = isset($_SERVER["PHP_AUTH_USER"]) ? $_SERVER["PHP_AUTH_USER"] : NULL;
$storedPass = isset($_SERVER["PHP_AUTH_PW"]) ? $_SERVER["PHP_AUTH_PW"] : NULL;

if ($storedUser !== $user || $storedPass !== $pass) {
    header("WWW-Authenticate: Basic realm='Authentication Required'");
    header("HTTP/1.0 401 Unauthorized");
    echo 'Authentication Required';
    exit;
}
