<?php 
include '../funcoes.php';
$id = $_GET['id'];
deleta_conta($conexao,$id);
header('Location: painel.php?pagina=conta');