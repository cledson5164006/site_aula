<?php
include("conexao.php");

$filme_id = $_POST["filme_id"] ?? ''; // Changed from $id to $filme_id

if (empty($filme_id)) {
    header("Location: cadastroFilmes.php?message=empty_fields");
    exit();
}

// CORRECTED: Use 'filme' column in WHERE clause
$sql = "DELETE FROM filmes WHERE filme = ?";
$stmt = $conn->prepare($sql);

if ($stmt) {
   $stmt->bind_param("i", $filme_id); // "i" for integer, assuming 'filme' is an integer
   if($stmt->execute()){
      header("Location: cadastroFilmes.php?message=deleted");
      exit();
   } else {
      error_log("Erro ao apagar filme: " . $stmt->error);
      header("Location: cadastroFilmes.php?message=delete_error");
      exit();
   }
   $stmt->close();
} else {
   error_log("Erro na preparação da consulta de exclusão de filme: " . $conn->error);
   header("Location: cadastroFilmes.php?message=delete_error");
   exit();
}

if (isset($conn) && $conn) {
    $conn->close();
}
?>