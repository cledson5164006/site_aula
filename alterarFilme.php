<?php
include("conexao.php");

// Get the POST data
$filme_id = $_POST["filme_id"] ?? ''; // The ID of the movie to be updated, changed from $id
$nome = $_POST["nome"] ?? '';
$ano = $_POST["ano"] ?? '';
$genero = $_POST["genero"] ?? ''; // The new genre ID

// Basic validation
if (empty($filme_id) || empty($nome) || empty($ano) || empty($genero)) {
    header("Location: cadastroFilmes.php?message=empty_fields");
    exit();
}

// Prepare the SQL UPDATE statement
// CORRECTED: Use 'filme' column in WHERE clause
$sql = "UPDATE filmes SET nome = ?, ano = ?, genero = ? WHERE filme = ?";
$stmt = $conn->prepare($sql);

// Check if the statement was prepared successfully
if ($stmt) {
    // Bind parameters
    // "ssii" means: nome (string), ano (integer), genero (integer), filme_id (integer)
    $stmt->bind_param("siii", $nome, $ano, $genero, $filme_id);

    // Execute the statement
    if ($stmt->execute()) {
        header("Location: cadastroFilmes.php?message=update_success");
        exit();
    } else {
        error_log("Erro ao executar a atualização do filme: " . $stmt->error);
        header("Location: cadastroFilmes.php?message=update_error");
        exit();
    }
    $stmt->close();
} else {
    error_log("Erro na preparação da consulta de atualização de filme: " . $conn->error);
    header("Location: cadastroFilmes.php?message=update_error");
    exit();
}

if (isset($conn) && $conn) {
    $conn->close();
}
?>