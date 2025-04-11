<?php
// /src/auth/logout.php

require_once __DIR__ . '/../includes/session.php'; // Handles session_start()

// Kill the session
$_SESSION = [];
session_unset();
session_destroy();

// Prevent back button cache
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Expires: Thu, 01 Jan 1970 00:00:00 GMT");

// Redirect to homepage (update path if using a frontend router or custom layout)
header("Location: /index.php");
exit;
