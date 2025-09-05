<?php
// connect to session
session_start();

// wipe the session data
$_SESSION = [];

// destroy session cookie on client
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// destroy session object in server
session_destroy();

// redirect to page
header("Location: /");
exit;