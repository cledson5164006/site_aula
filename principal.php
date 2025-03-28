<?php
include("autenticacao.php");

echo"cpf: ".$_SESSION['cpf']. '<br>';
echo"nome: ".$_SESSION['nome']. '<br>';
echo"senha: ".$_SESSION['senha'];
?>
