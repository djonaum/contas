<?php 
include '../funcoes.php';
$id = $_GET['id'];
deleta_conta_variavel($conexao,$id);
header('Location: painel.php?pagina=conta_variavel');