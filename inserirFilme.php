<?php
// For debugging: display all errors. In production, disable this.
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

include("conexao.php"); // Include your database connection

// Get and sanitize POST data
$nome = htmlspecialchars(trim($_POST['nome'] ?? ''));
$ano = htmlspecialchars(trim($_POST['ano'] ?? ''));
$genero_id = htmlspecialchars(trim($_POST['genero'] ?? '')); // This will be the ID of the selected genre

// --- DEBUGGING: Check received POST data ---
// echo "DEBUG: Nome: " . $nome . "<br>";
// echo "DEBUG: Ano: " . $ano . "<br>";
// echo "DEBUG: Genero ID: " . $genero_id . "<br>";
// die("DEBUG: Check values above."); // Uncomment this to see if data is received

// Basic validation
if (empty($nome) || empty($ano) || empty($genero_id)) {
    // You might want to redirect back to cadastroFilmes.php with an error message
    // Instead of die, redirect with a message
    header("Location: cadastroFilmes.php?message=empty_fields");
    exit();
}

// Prepare the SQL INSERT statement
// Ensure your 'filmes' table has 'nome', 'ano', and 'genero' columns (CHANGED genero_id to genero)
$sql = "INSERT INTO filmes (nome, ano, genero) VALUES (?, ?, ?)"; // CORRECTED LINE HERE
$stmt = $conn->prepare($sql);

// Check if the statement was prepared successfully
if ($stmt === false) {
    // Log the error for internal review
    error_log("Erro na preparação da consulta: " . $conn->error);
    // Redirect with a more generic error message
    header("Location: cadastroFilmes.php?message=insert_error_prepare"); // Specific error message
    exit();
}

// Bind parameters (s for string, i for integer)
// Assuming 'ano' and 'genero_id' are integers in your database table
// --- DEBUGGING: Check if bind_param has issues ---
if (!$stmt->bind_param("sii", $nome, $ano, $genero_id)) {
    error_log("Erro ao vincular parâmetros: " . $stmt->error);
    header("Location: cadastroFilmes.php?message=insert_error_bind"); // Specific error message
    exit();
}

// Execute the statement
if ($stmt->execute()) {
    // Redirect on success
    header("Location: cadastroFilmes.php?message=success");
    exit();
} else {
    // Redirect on error
    error_log("Erro ao inserir filme: " . $stmt->error);
    header("Location: cadastroFilmes.php?message=insert_failed");
    exit();
}

// Close the statement and connection
if (isset($stmt) && $stmt) { // Added check for $stmt to prevent errors if prepare failed
    $stmt->close();
}
if (isset($conn) && $conn) { // Added check for $conn
    $conn->close();
}
?>