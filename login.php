<?php
ini_set("display_errors","on");
include("conexao.php");

$cpf=$_POST["cpf"];
$senha=$_POST["senha"];

if(!isset($_POST['cpf']) || $_POST['cpf'] == ''){
    die("insira um cpf");
}

if(!isset($_POST['senha']) || $_POST['senha'] == ''){
    die("insira uma senha ");

<<<<<<< HEAD
}

$sql = "select nome from usuarios where cpf ='$cpf' and senha = '$senha'";
=======
}$sql = "select nome from usuarios where cpf ='$cpf' and senha = '$senha '";
>>>>>>> 9746033914b6219f32383eb92b1f5863ea44ff58

$resultado = $conn->query($sql);
$row = $resultado->fetch_assoc();

if(isset($row) && $row['nome'] != ''){
session_start();

$_SESSION["cpf"] = $cpf;
$_SESSION["senha"] = $senha;
$_SESSION["nome"] = $row['nome'];

header("Location: principal.php");
die;
}else{
    echo "senha incorreta";
}








