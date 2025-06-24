<?php
include("conexao.php"); //

// Get the POST data
$cpf = $_POST["cpf"] ?? ''; // New CPF
$nome = $_POST["nome"] ?? ''; // New Nome
$senha = $_POST["senha"] ?? ''; // New Senha
$cpfAnterior = $_POST["cpfAnterior"] ?? ''; // Original CPF for WHERE clause

// Basic validation: Check if essential fields are not empty
if (empty($cpf) || empty($nome) || empty($senha) || empty($cpfAnterior)) {
    header("Location: cadastroUsuarios.php?message=empty_fields"); // Redirect with error
    exit(); // Always exit after redirect
}

// Prepare the SQL UPDATE statement using placeholders (?)
$sql = "UPDATE usuarios SET cpf = ?, senha = ?, nome = ? WHERE cpf = ?"; //
$stmt = $conn->prepare($sql); //

// Check if the statement was prepared successfully
if ($stmt) { //
    // Bind the parameters to the placeholders
    // "ssss" indicates four string parameters (cpf, senha, nome, cpfAnterior)
    $stmt->bind_param("ssss", $cpf, $senha, $nome, $cpfAnterior); //

    // Execute the statement
    if ($stmt->execute()) { //
        // Redirect on success with an 'updated' message
        header("Location: cadastroUsuarios.php?message=updated"); //
        exit(); // Always exit after a header redirect
    } else {
        // Handle execution error
        error_log("Erro ao executar a atualização do usuário: " . $stmt->error); // Log error
        header("Location: cadastroUsuarios.php?message=update_error"); // Redirect with error
        exit(); // Exit after redirect
    }
    // Close the statement
    $stmt->close(); //
} else {
    // Handle prepare error
    error_log("Erro na preparação da consulta de atualização: " . $conn->error); // Log error
    header("Location: cadastroUsuarios.php?message=update_error"); // Redirect with error
    exit(); // Exit after redirect
}

// Close the database connection
if (isset($conn) && $conn) {
    $conn->close();
}
?>