<?php
session_start();
session_destroy();
header("Location: /IkiNet/app/views/login.php");
exit();