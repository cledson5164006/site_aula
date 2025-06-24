<?php

include("autenticacao.php");
include("conexao.php");

$nome = $_POST['nome'];
$cpf = $_POST['cpf'];
$senha = $_POST['senha'];

$sql = "INSERT INTO usuarios (nome, cpf, senha) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("sss", $nome, $cpf, $senha);
    if ($stmt->execute()) {
        header("Location: cadastroUsuarios.php");
        die();
    } else {
        echo "erro";
    }
    $stmt->close();
} else {
    echo "erro";
}

$conn->close();

?>