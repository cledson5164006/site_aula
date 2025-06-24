<?php
session_start();

if (empty($_SESSION['cpf']) || empty($_SESSION['senha'])) {
    header("Location: index.php");
    exit();
}
?>