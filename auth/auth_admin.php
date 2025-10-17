<?php
// auth/auth_admin.php
session_start();

if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
    header("Location: ../sessao/403.php");
    exit;
}
?>