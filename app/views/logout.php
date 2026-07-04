<?php
require_once __DIR__ . "/../helpers/auth.php";
ensureSessionStarted();
session_destroy();
header("Location: /IkiNet/app/views/login.php");
exit();