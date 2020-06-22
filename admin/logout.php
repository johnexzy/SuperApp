<?php
session_start();

if (isset($_SESSION['user'])) {
 unset($_SESSION['user']);
 header("Location: /admin/index.php");
}

header("Location: /admin/index.php");
