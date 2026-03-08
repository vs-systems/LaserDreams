<?php
require __DIR__ . '/../includes/auth.php';
session_start();
session_destroy();
header('Location: login.php');
exit;
