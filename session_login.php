<?php
// connect to session
session_start();

// set session status
$_SESSION["status"] = "logged in";

// redirect to page
header("Location: /");
exit;