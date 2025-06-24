<?php

include("conexao.php");

$cpf = $_POST["cpf"] ?? ''; // Use null coalescing operator for safer access
$senha = $_POST["senha"] ?? ''; // Use null coalescing operator for safer access

if ($cpf == '') {
    die("insira um cpf");
}

if ($senha == '') {
    die("insira uma senha ");
}

// Corrected table name from 'uarios' to 'usuarios' and removed trailing space in password check
$sql = "SELECT nome FROM usuarios WHERE cpf = ? AND senha = ?"; // Using prepared statements for security

// Prepare the statement
$stmt = $conn->prepare($sql);

// Bind parameters
$stmt->bind_param("ss", $cpf, $senha); // "ss" indicates two string parameters

// Execute the statement
$stmt->execute();

// Get the result
$resultado = $stmt->get_result();
$row = $resultado->fetch_assoc();

if (isset($row) && $row['nome'] != '') {
    session_start();
    $_SESSION["cpf"] = $cpf;
    $_SESSION["senha"] = $senha;
    $_SESSION["nome"] = $row['nome'];
    header("Location: principal.php"); // Corrected 'location :' to 'Location:'
    exit(); // Always exit after a header redirect
} else {
    echo "senha incorreta";
}

// Close the statement and connection
$stmt->close();
$conn->close();

?>