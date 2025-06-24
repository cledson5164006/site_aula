<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

include("conexao.php");

$cpf = htmlspecialchars(trim($_POST["cpf"] ?? ''));
$nome = htmlspecialchars(trim($_POST["nome"] ?? ''));
$senha = htmlspecialchars(trim($_POST["senha"] ?? ''));

if (empty($cpf) || empty($nome) || empty($senha)) {
    header("Location: cadastroUsuarios.php?message=empty_fields"); // CHANGED HERE
    exit();
}

$sqlValida = "SELECT COUNT(*) AS qt FROM usuarios WHERE cpf = ?";
$stmtValida = $conn->prepare($sqlValida);

if ($stmtValida === false) {
    error_log("Erro na preparação da consulta de validação de CPF: " . $conn->error);
    header("Location: cadastroUsuarios.php?message=validation_error"); // CHANGED HERE
    exit();
}

$stmtValida->bind_param("s", $cpf);
$stmtValida->execute();
$resultadoValida = $stmtValida->get_result();
$rowValida = $resultadoValida->fetch_assoc();
$qt = $rowValida['qt'];
$stmtValida->close();

if ($qt > 0) {
    header("Location: cadastroUsuarios.php?message=user_exists"); // CHANGED HERE
    exit();
}

$sqlInsert = "INSERT INTO usuarios (cpf, nome, senha) VALUES (?, ?, ?)";
$stmtInsert = $conn->prepare($sqlInsert);

if ($stmtInsert === false) {
    error_log("Erro na preparação da consulta de INSERT: " . $conn->error);
    header("Location: cadastroUsuarios.php?message=insert_error"); // CHANGED HERE
    exit();
}

$stmtInsert->bind_param("sss", $cpf, $nome, $senha);

if (!$stmtInsert->execute()) {
    error_log("Erro ao inserir usuário: " . $stmtInsert->error);
    header("Location: cadastroUsuarios.php?message=insert_failed"); // CHANGED HERE
    exit();
}

$stmtInsert->close();
$conn->close();

header("Location: cadastroUsuarios.php?message=success");
exit();
?>