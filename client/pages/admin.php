<?php
session_start();
if (!isset($_SESSION['username']) || !$_SESSION['admin']) {
    header('Location: login.php');
    exit;
}