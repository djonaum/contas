<?php 
include '../funcoes.php';
$id = $_GET['id'];
deleta_conta_fixa($conexao,$id);
header('Location: painel.php?pagina=conta_fixa');