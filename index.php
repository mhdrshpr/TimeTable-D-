<?php
require_once 'db.php';
init_secure_session();

if (isset($_SESSION['username']) && isset($_SESSION['user_id'])) {
    header("Location: timetable.php");
    exit;
} else {
    header("Location: login.php");
    exit;
}
?>